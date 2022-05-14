<?php

namespace App\Mail;

use App\Models\CompanySetting;
use App\Models\Payment;
use App\Models\SplurgeAccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email;

class PaymentCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $payment;

    private $splurgeAccessToken;

    private $companySetting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Payment $payment, CompanySetting $companySetting, SplurgeAccessToken $splurgeAccessToken)
    {
        $this->payment = $payment;
        $this->splurgeAccessToken = $splurgeAccessToken;
        $this->companySetting = $companySetting;
        $this->afterCommit();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         $this->markdown('emails.payments.created')
        ->with([
            'payment' => $this->payment,
            'accessUrl' => $this->splurgeAccessToken->toAutoLoginURL(),
            'companySetting' => $this->companySetting,
        ])->subject(sprintf("%s: New Payment for booking", config("app.name")));
        return $this;
    }
}
