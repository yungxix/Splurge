<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Http\Requests\My\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Repositories\BookingsRepository;
use Illuminate\Http\Request;
use App\Models\Payment;

class BookingsController extends Controller
{
    private $repository;
    public function __construct(BookingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request) {
        $bookings = $this->repository->findAllForUser($request)->get();
        return view('my.screens.bookings.index', ['bookings' => $bookings]);
    }

    public function show(Request $request, Booking $booking) {
        $booking = $booking->loadMissing(['customer', 'location', 'serviceTier', 'serviceTier.service']);
        $totalPaid = Payment::where('booking_id', $booking->id)->sum('amount');
        return view('my.screens.bookings.show', [
            'booking' => $booking,
            'total_paid' => $totalPaid]);
    }

    public function update(BookingRequest $request, Booking $booking) {
        $booking = $request->updateBooking($booking);
        if ($request->wantsJson()) {
            return new BookingResource($booking);
        }
        $request->session()->flash('success_message', 'Updated booking');
        return redirect()->route('my.bookings.show', $booking);
    }
}
