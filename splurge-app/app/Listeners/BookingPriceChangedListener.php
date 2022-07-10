<?php

namespace App\Listeners;

use App\Events\BookingPriceChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookingPriceChangedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\BookingPriceChangedEvent  $event
     * @return void
     */
    public function handle(BookingPriceChangedEvent $event)
    {
        
    }
}
