<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessPaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $date;
    public $items;
    public $subtotal;

    public function __construct($order, $date, $items, $subtotal)
    {
        $this->order = $order;
        $this->date = $date;
        $this->items = $items;
        $this->subtotal = $subtotal;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->withSymfonyMessage(function ($message) {
                $message->getHeaders()->addTextHeader('X-MailerSend-Template-ID', 'jpzkmgqq9rng059v');
                $message->getHeaders()->addTextHeader('X-MailerSend-Variables', json_encode([
                    'order_number' => $this->order,
                    'date' => $this->date,
                    'instance' => [
                        'name' => $this->items['name'],
                        'price' => $this->items['price'],
                    ],
                    'sub_total' => $this->subtotal,
                ]));
            });
    }
}
