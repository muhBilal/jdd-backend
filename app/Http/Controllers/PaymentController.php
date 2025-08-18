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
            $payload = $request->all();
            $eventTicket = DB::table('event_tickets')->where('id', $payload['eventTicketId'])->first();
            $event       = DB::table('events')->where('id', $eventTicket->event_id)->first();
            $price = $eventTicket->price * $payload['qty'] + ($eventTicket->price * $payload['qty'] * 0.07);
            $transactionId = Str::uuid()->toString();

            $payment = $this->ipaymu->createDirectPayment(
                $price,
                $payload['name'],
                $payload['phone'],
                $payload['email'],
                $transactionId
            );

            $data = $payment['Data'] ?? [];
            ProcessTicketTransaction::dispatch($payload, $this->ipaymu);

            return response()->json([
                'status'  => true,
                'message' => 'Transaction queued for processing',
                'data'    => [
                    'transaction' => [
                        'id'                => $transactionId,
                        'event_id'          => $event->id,
                        'amount'            => $price,
                        'payment_url'       => $data['QrImage'] ?? null,
                        'payment_reference' => $data['ReferenceId'] ?? null,
                        'payment_expired_at' => $data['Expired'] ?? null,
                    ],
                    'payment' => $payment,
                    'request' => $payload
                ]
            ], 202);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
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
