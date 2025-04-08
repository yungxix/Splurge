<?php

namespace App\Listeners;

use App\Events\PaymentCreatedEvent;
use App\Mail\PaymentCreated;
// use App\Models\Booking;
use App\Models\Communication;
use App\Repositories\SplurgeAccessTokenRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Models\CompanySetting;

class PaymentCreatedHandler implements ShouldQueue
{
    private $splurgeRepo;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SplurgeAccessTokenRepository $splurgeRepo)
    {
        $this->splurgeRepo = $splurgeRepo;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PaymentCreatedEvent $event)
    {
        // $booking = $event->payment->booking;
        // $mailable = new PaymentCreated($event->payment,
        // CompanySetting::first(),
        //  $this->splurgeRepo->findForModel($booking));
        
        //  Mail::to($booking->customer->email)
        //         ->send($mailable);

        // $comm = new Communication();
        // $comm->sender = "company";
        // $comm->receiver = sprintf("%s %s<%s>", $booking->customer->first_name,
        //                     $booking->customer->last_name, $booking->customer->email);


        // $comm->content = $mailable->render();
        // $comm->channel_type = Booking::class;
        // $comm->channel_id = $event->payment->booking_id;
        // $comm->subject = sprintf("New Payment for Booking #%s", $booking->code);
        // $comm->save();
    }
}
