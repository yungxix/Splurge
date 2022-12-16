<?php

namespace App\Observers;

use App\Models\CustomerEventGuest;

class CustomerEventGuestObserver
{
    /**
     * Handle the CustomerEventGuest "created" event.
     *
     * @param  \App\Models\CustomerEventGuest  $customerEventGuest
     * @return void
     */
    public function created(CustomerEventGuest $customerEventGuest)
    {
        $customerEventGuest->generateBarcode(true);
    }

    /**
     * Handle the CustomerEventGuest "updated" event.
     *
     * @param  \App\Models\CustomerEventGuest  $customerEventGuest
     * @return void
     */
    public function updated(CustomerEventGuest $customerEventGuest)
    {
        //
    }

    /**
     * Handle the CustomerEventGuest "deleted" event.
     *
     * @param  \App\Models\CustomerEventGuest  $customerEventGuest
     * @return void
     */
    public function deleted(CustomerEventGuest $customerEventGuest)
    {
        //
    }

    /**
     * Handle the CustomerEventGuest "restored" event.
     *
     * @param  \App\Models\CustomerEventGuest  $customerEventGuest
     * @return void
     */
    public function restored(CustomerEventGuest $customerEventGuest)
    {
        //
    }

    /**
     * Handle the CustomerEventGuest "force deleted" event.
     *
     * @param  \App\Models\CustomerEventGuest  $customerEventGuest
     * @return void
     */
    public function forceDeleted(CustomerEventGuest $customerEventGuest)
    {
        //
    }
}
