<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Communication;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(Request $request, Booking $booking) {
        $messages = $booking->messages()
        ->where('internal', '<>', true)
        ->select("id", "created_at", "subject", "receiver", "sender")
        ->orderBy('created_at', 'desc')->get();
        return view('my.screens.bookings.messages.index', ['booking' => $booking, 'messages' => $messages]);
    }

    public function show(Request $request, Booking $booking, Communication $message) {
        return view('my.screens.bookings.messages.show', ['booking' => $booking, 'message' => $message]);
    }

    public function showContent(Request $request, Booking $booking, Communication $message) {
        return response($message->content);
    }
}
