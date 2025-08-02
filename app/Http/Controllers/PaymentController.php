<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IpaymuService;
use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

class PaymentController extends Controller
{
    protected $ipaymu;

    public function __construct(IpaymuService $ipaymu)
    {
        $this->ipaymu = $ipaymu;
    }

    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'name'   => 'required|string',
            'phone'  => 'required|string',
            'email'  => 'required|email',
        ]);

        $payment = $this->ipaymu->createDirectPayment(
            $request->amount,
            $request->name,
            $request->phone,
            $request->email
        );

        return response()->json($payment);
    }

    public function notify(Request $request)
    {
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
