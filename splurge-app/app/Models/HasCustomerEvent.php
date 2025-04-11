<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\CustomerEvent;
trait HasCustomerEvent {

    // public function isCustomerEventExists() {
    //     return !CustomerEvent::where('booking_id', $this->id)
    //             ->selectRaw("1 as one")
    //             ->take(1)
    //             ->get()->isEmpty();
    // }

    // public function createDefaultCustomerEvent() {
    //     return CustomerEvent::create([
    //         'name' => Str::limit($this->description, 150, '...'),
    //         'booking_id' => $this->id,
    //         'event_date' => $this->event_date
    //     ]); 
    // }

}