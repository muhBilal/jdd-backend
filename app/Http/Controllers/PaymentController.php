<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTicketTransaction;
use Illuminate\Http\Request;
use App\Services\IpaymuService;
use Illuminate\Support\Facades\DB;
use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PaymentController extends Controller
{
    protected $ipaymu;

    public function __construct(IpaymuService $ipaymu)
    {
        $this->ipaymu = $ipaymu;
    }

    public function process(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'eventTicketId' => 'required|uuid|exists:event_tickets,id',
                'tickets' => 'required|array|min:1',
                'tickets.*.eventFormId' => 'required|uuid|exists:event_forms,id',
                'tickets.*.value' => 'required|string',
                'qty' => 'required|integer|min:1'
            ]);

            DB::beginTransaction();

            $eventTicket = DB::table('event_tickets')->where('id', $validated['eventTicketId'])->first();
            if (!$eventTicket) {
                return response()->json(['message' => 'Invalid eventTicketId.'], 404);
            }

            $event = DB::table('events')->where('id', $eventTicket->event_id)->first();
            if (!$event) {
                return response()->json(['message' => 'Invalid event ID.'], 404);
            }

            $user = DB::table('users')->where('email', $validated['email'])->first();
            if (!$user) {
                $userId = Str::uuid()->toString();
                DB::table('users')->insert([
                    'id' => $userId,
                    'email' => $validated['email'],
                    'password_hash' => Hash::make('password'),
                    'full_name' => $validated['name'],
                    'auth_provider' => 'email'
                ]);
                $user = DB::table('users')->where('id', $userId)->first();
            }

            $insertedTicketIds = [];
            $price = $eventTicket->price * $validated['qty'] + ($eventTicket->price * $validated['qty'] * 0.07);
            $transactionId = Str::uuid()->toString();

            DB::table('transactions')->insert([
                'id'       => $transactionId,
                'event_id' => $event->id,
                'user_id'  => $user->id,
                'amount' => $price,
                'payment_url' => null,
                'payment_reference' => null,
                'payment_expired_at' => null,
                'status' => 'pending'
            ]);

            for ($i = 0; $i < $validated['qty']; $i++) {
                $ticketId = Str::uuid()->toString();
                DB::table('tickets')->insert([
                    'id' => $ticketId,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'code' => Str::random(10),
                    'event_ticket_id' => $validated['eventTicketId'],
                ]);
                DB::table('ticket_users')->insert([
                    'ticket_id' => $ticketId,
                    'user_id'   => $user->id,
                ]);
                foreach ($validated['tickets'] as $t) {
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
                ->where('id', $validated['eventTicketId'])
                ->decrement('quota', $validated['qty']);

            DB::commit();

            $payment = $this->ipaymu->createDirectPayment(
                $price,
                $validated['name'],
                $validated['phone'],
                $validated['email'],
                $transactionId
            );

            if ($payment['Status'] !== 200) {
                DB::table('transactions')->where('id', $transactionId)->update([
                    'status' => 'payment_failed'
                ]);

                return response()->json([
                    'message' => 'Payment failed',
                    'error'   => $payment['Message']
                ], 500);
            }

            $data = $payment['Data'];
            DB::table('transactions')->where('id', $transactionId)->update([
                'payment_url' => $data['QrImage'],
                'payment_reference' => $data['ReferenceId'],
                'payment_expired_at' => $data['Expired'],
                'status' => 'pending'
            ]);

            return response()->json([
                'message' => 'Transaction processed successfully',
                'transaction_id' => $transactionId,
                'ticket_ids' => $insertedTicketIds,
                'payment' => $payment
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Transaction failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    public function notify(Request $request)
    {
        $status = $request->input('status');
        $referenceId = $request->input('reference_id');
        $transaction = DB::table('transactions')->where('payment_reference', $referenceId)->first();
        // $event = DB::table('events')->where('id', $transaction->event_id)->first();

        if (!$transaction) {
            return response()->json(['status' => 'not found']);
        }
        DB::table('transactions')->where('payment_reference', $referenceId)->update([
            'status' => $status,
        ]);


        $mailersend = new MailerSend([
            'api_key' => env('MAILERSEND_API_KEY'),
        ]);
        $orderNumber = $transaction->id;
        $date = now()->format('d M Y');
        // $instance = [

        return response()->json(['status' => 'ok']);
    }

    public function sendPaymentSuccess(Request $request)
    {
        $mailersend = new MailerSend([
            'api_key' => env('MAILERSEND_API_KEY'),
        ]);

        $orderNumber = $request->input('order_number', 'ORD-' . now()->format('YmdHis'));
        $date = now()->format('d M Y');
        $instance = [
            'name' => $request->input('product_name', 'Premium Subscription'),
            'price' => $request->input('product_price', 'Rp 150.000'),
        ];
        $subTotal = $request->input('sub_total', 'Rp 150.000');
        $recipientEmail = $request->input('email', 'customer@example.com');
        $recipientName = $request->input('name', 'Customer');

        $personalization = [
            new Personalization($recipientEmail, [
                'order_number' => $orderNumber,
                'date' => $date,
                'instance' => $instance,
                'sub_total' => $subTotal,
            ])
        ];

        $recipients = [
            new Recipient($recipientEmail, $recipientName),
        ];

        $emailParams = (new EmailParams())
            ->setFrom(env('MAIL_FROM_ADDRESS'))
            ->setFromName(env('MAIL_FROM_NAME', 'JDD Official'))
            ->setRecipients($recipients)
            ->setSubject('Payment Successful')
            ->setTemplateId('jpzkmgqq9rng059v')
            ->setPersonalization($personalization);

        $mailersend->email->send($emailParams);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment success email sent.',
            'data' => [
                'order_number' => $orderNumber,
                'email' => $recipientEmail,
            ],
        ]);
    }
}
