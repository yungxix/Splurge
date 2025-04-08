<?php

namespace App\Mail;

// use App\Models\Booking;
use App\Models\CompanySetting;
use App\Models\SplurgeAccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email;

class BookingCreated extends Mailable
{
    use Queueable, SerializesModels;

    private $booking;
    private $companySetting;
    private $splurgeAccessToken;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(/*Booking $booking, */CompanySetting $companySetting, SplurgeAccessToken $splurgeAccessToken)
    {
        $this->afterCommit();

        // $this->booking = $booking;
        $this->companySetting = $companySetting;
        $this->splurgeAccessToken = $splurgeAccessToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $this->markdown('emails.bookings.created')->with([
            'booking' => $this->booking,
            'accessUrl' => $this->splurgeAccessToken->toAutoLoginURL(),
            'companySetting' => $this->companySetting
        ])
        ->tag("booking")
        ->subject(sprintf("%s: New Booking #%s", config("app.name"), $this->booking->code))
        ->metadata("booking-id", $this->booking->id);
        
        return $this;
    }
}
