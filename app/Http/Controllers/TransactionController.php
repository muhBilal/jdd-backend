<?php

namespace App\Http\Controllers;

use App\Services\IpaymuService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    protected $ipaymu;

    public function __construct(IpaymuService $ipaymu)
    {
        $this->ipaymu = $ipaymu;
    }

    public function process(Request $request)
    {
        try {
            $name = $request->name;
            $email = $request->email;
            $phone = $request->phone;
            $eventTicketId = $request->eventTicketId;
            $amount = (int) $request->amount;
            $tickets = $request->tickets;
            $qty = $request->qty;

            if (!is_array($tickets) || empty($tickets)) {
                return response()->json([
                    'message' => 'Tickets data is required and must be an array'
                ], 400);
            }

            $eventTicket = DB::table('event_tickets')->where('id', $eventTicketId)->first();
            if (!$eventTicket) {
                return response()->json([
                    'message' => 'Invalid eventTicketId. Event ticket does not exist.'
                ], 404);
            }

            $event = DB::table('events')->where('id', $eventTicket->event_id)->first();
            if (!$event) {
                return response()->json([
                    'message' => 'Invalid event ID. Event does not exist.'
                ], 404);
            }

            DB::beginTransaction();
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
                $user = DB::table('users')->where('id', $userId)->first();
            }

            $insertedTicketIds = [];
            $price = $eventTicket->price * $qty + ($eventTicket->price * $qty * 0.07);
            $transactionId = Str::uuid()->toString();
            $payment = $this->ipaymu->createDirectPayment(
                $price,
                $name,
                $phone,
                $email,
                $transactionId
            );

            if ($payment['Status'] === 'success') {
                return response()->json([
                    'message' => 'Payment failed',
                    'error'   => $payment['Message']
                ], 500);
            }

            $data = $payment['Data'];
            DB::table('transactions')->insert([
                'id'       => $transactionId,
                'event_id' => $event->id,
                'user_id'  => $user->id,
                'amount' => $price,
                'payment_url' => $data['QrImage'],
                'payment_reference' => $data['ReferenceId'],
                'payment_expired_at' => $data['Expired'],
            ]);

            for ($i = 0; $i < $qty; $i++) {
                $ticketId = Str::uuid()->toString();
                DB::table('tickets')->insert([
                    'id' => $ticketId,
                    'name' => $name,
                    'email' => $email,
                    'code' => Str::random(10),
                    'event_ticket_id' => $eventTicketId,
                ]);

                DB::table('ticket_users')->insert([
                    'ticket_id' => $ticketId,
                    'user_id'   => $user->id,
                ]);

                foreach ($tickets as $t) {
                    DB::table('event_form_tickets')->insert([
                        'event_form_id' => $t['eventFormId'],
                        'ticket_id'     => $ticketId,
                        'value'         => $t['value'],
                    ]);
                }

                $insertedTicketIds[] = $ticketId;

                DB::table('transaction_tickets')->insert([
                    'transaction_id' => $transactionId,
                    'ticket_id'      => $ticketId,
                    'quantity'       => 1,
                    'price_at_purchase' => $eventTicket->price,
                ]);
            }

            DB::table('event_tickets')
                ->where('id', $eventTicketId)
                ->decrement('quota', $qty);
            DB::commit();
            return response()->json([
                'message' => 'Transaction processed successfully',
                'transaction_id' => $transactionId,
                'ticket_ids' => $insertedTicketIds,
                'data' => $request->all(),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Transaction failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


}
