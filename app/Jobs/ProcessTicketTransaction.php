<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTicketTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;
    protected $ipaymu;

    public function __construct($payload, $ipaymu)
    {
        $this->payload = $payload;
        $this->ipaymu  = $ipaymu;
    }

    public function handle()
    {
        $name          = $this->payload['name'];
        $email         = $this->payload['email'];
        $phone         = $this->payload['phone'];
        $eventTicketId = $this->payload['eventTicketId'];
        $tickets       = $this->payload['tickets'];
        $qty           = $this->payload['qty'];

        $eventTicket = DB::table('event_tickets')->where('id', $eventTicketId)->first();
        $event       = DB::table('events')->where('id', $eventTicket->event_id)->first();

        DB::beginTransaction();
        try {
            $user = DB::table('users')->where('email', $email)->first();
            if (!$user) {
                $userId = Str::uuid()->toString();
                DB::table('users')->insert([
                    'id' => $userId,
                    'email' => $email,
                    'password_hash' => Hash::make('password'),
                    'full_name' => $name,
                    'auth_provider' => 'email'
                ]);
                $user = (object)['id' => $userId];
            }

            $insertedTicketIds = [];
            $price = $eventTicket->price * $qty + ($eventTicket->price * $qty * 0.07);
            $transactionId = Str::uuid()->toString();

            $payment = $this->ipaymu->createDirectPayment(
                $price, $name, $phone, $email, $transactionId
            );

            $data = $payment['Data'] ?? [];

            DB::table('transactions')->insert([
                'id'       => $transactionId,
                'event_id' => $event->id,
                'user_id'  => $user->id,
                'amount' => $price,
                'payment_url' => $data['QrImage'] ?? null,
                'payment_reference' => $data['ReferenceId'] ?? null,
                'payment_expired_at' => $data['Expired'] ?? null,
            ]);

            $ticketRows = [];
            $ticketUserRows = [];
            $eventFormRows = [];
            $transactionTicketRows = [];

            for ($i = 0; $i < $qty; $i++) {
                $ticketId = Str::uuid()->toString();

                $ticketRows[] = [
                    'id' => $ticketId,
                    'name' => $name,
                    'email' => $email,
                    'code' => Str::random(10),
                    'event_ticket_id' => $eventTicketId,
                ];

                $ticketUserRows[] = [
                    'ticket_id' => $ticketId,
                    'user_id'   => $user->id,
                ];

                foreach ($tickets as $t) {
                    $eventFormRows[] = [
                        'event_form_id' => $t['eventFormId'],
                        'ticket_id'     => $ticketId,
                        'value'         => $t['value'],
                    ];
                }

                $transactionTicketRows[] = [
                    'transaction_id' => $transactionId,
                    'ticket_id'      => $ticketId,
                    'quantity'       => 1,
                    'price_at_purchase' => $eventTicket->price,
                ];

                $insertedTicketIds[] = $ticketId;
            }

            DB::table('tickets')->insert($ticketRows);
            DB::table('ticket_users')->insert($ticketUserRows);
            DB::table('event_form_tickets')->insert($eventFormRows);
            DB::table('transaction_tickets')->insert($transactionTicketRows);

            DB::table('event_tickets')
                ->where('id', $eventTicketId)
                ->decrement('quota', $qty);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
