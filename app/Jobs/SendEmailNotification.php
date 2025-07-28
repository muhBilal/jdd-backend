<?php
namespace App\Jobs;

use App\Mail\TransactionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailNotification implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $user;
    protected $transaction;

    public function __construct($user, $transaction)
    {
        $this->user = $user;
        $this->transaction = $transaction;
    }

    public function handle(Mailer $mailer)
    {
        $mailer->to($this->user->email)
               ->send(new TransactionNotification($this->transaction));
    }
}
