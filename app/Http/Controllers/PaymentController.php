<?php

namespace App\Http\Controllers;

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

    public function create(Request $request)
    {
        $request->validate([
            'name'   => 'required|string',
            'email'  => 'required|email',
            'phone'  => 'required|string',
            'amount' => 'required|numeric',
        ]);
        $amount = $request->input('amount');
        // $amountTotal = $amount + ($amount * 0.07); 
        $amountTotal = $amount;
        // dd("amount total: " . $amountTotal);
        $ref = uniqid('order_');
        $payment = $this->ipaymu->createDirectPayment(
            $amountTotal,
            $request->name,
            $request->phone,
            $request->email,
            $ref
        );

        return response()->json($payment);
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
