<?php

namespace App\Observers;

use App\Models\CustomerEvent;
use App\Models\Booking;
use App\Models\CustomerEventGuest;

class CustomerEventObserver
{
    /**
     * Handle the CustomerEvent "created" event.
     *
     * @param  \App\Models\CustomerEvent  $customerEvent
     * @return void
     */
    public function created(CustomerEvent $customerEvent)
    {
        //
    }

    /**
     * Handle the CustomerEvent "updated" event.
     *
     * @param  \App\Models\CustomerEvent  $customerEvent
     * @return void
     */
    public function updated(CustomerEvent $customerEvent)
    {
        //
    }

    /**
     * Handle the CustomerEvent "deleted" event.
     *
     * @param  \App\Models\CustomerEvent  $customerEvent
     * @return void
     */
    public function deleted(CustomerEvent $customerEvent)
    {
        $booking = Booking::where('id', $customerEvent->booking_id)->first();
        if (!is_null($booking)) {
            $booking->delete();
        }
        CustomerEventGuest::where('customer_event_id', $customerEvent->id)->delete();
    }

    /**
     * Handle the CustomerEvent "restored" event.
     *
     * @param  \App\Models\CustomerEvent  $customerEvent
     * @return void
     */
    public function restored(CustomerEvent $customerEvent)
    {
        //
    }

    /**
     * Handle the CustomerEvent "force deleted" event.
     *
     * @param  \App\Models\CustomerEvent  $customerEvent
     * @return void
     */
    public function forceDeleted(CustomerEvent $customerEvent)
    {
        //
    }
}
