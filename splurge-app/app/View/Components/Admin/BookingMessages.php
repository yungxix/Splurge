<?php

namespace App\View\Components\Admin;

// use App\Models\Booking;
use Illuminate\View\Component;

use App\Models\Communication;
use Illuminate\Http\Request;

class BookingMessages extends Component
{

    private $booking;
    private $perPage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(/*Booking $booking,*/ $perPage = NULL)
    {
        $this->booking = null;
        $this->perPage = $perPage ?? 5;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $messages = Communication::where([
            "channel_id" => -1,
            "channel_type" => "Models/Booking"
        ])->orderBy("created_at", "desc")->cursorPaginate($this->perPage, ['*'], 'message_cursor');

        return view('components.admin.booking-messages', ['messages' => $messages]);
    }
}
