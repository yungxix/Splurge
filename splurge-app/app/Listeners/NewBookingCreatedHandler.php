<?php

namespace App\Listeners;

use App\Events\NewBookingCreatedEvent;
use App\Mail\BookingCreated;
use App\Models\Booking;
use App\Models\CompanySetting;
use App\Repositories\SplurgeAccessTokenRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Models\Communication;

class NewBookingCreatedHandler implements ShouldQueue
{
    private $accessRepo;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SplurgeAccessTokenRepository $accessRepo)
    {
        $this->accessRepo = $accessRepo;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewBookingCreatedEvent $event)
    {
        $booking = $event->getBooking();

        $accessToken = $this->accessRepo->createFor($booking, $booking->event_date->addDays(30) );

        $companySetting = CompanySetting::first();

        $payload = new BookingCreated($booking, $companySetting, $accessToken);


        $subject = sprintf("New Booking (#%s)", $booking->code);

        


        Mail::to($booking->customer->email)
            ->send($payload);

            
        $communication = new Communication();

        $communication->subject = $subject;
        $communication->receiver = sprintf("%s %s<%s>", $booking->customer->first_name,
         $booking->customer->last_name, $booking->customer->email);
        $communication->sender = "company";
        $communication->content = $payload->render();
        $communication->channel_type = Booking::class;
        $communication->channel_id = $booking->id;

        $communication->save();
    }
}
