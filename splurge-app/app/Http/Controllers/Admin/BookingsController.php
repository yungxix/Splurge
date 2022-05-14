<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Repositories\BookingsRepository;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    private $repository;

    public function __construct(BookingsRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request) {
        $bookings = $this->repository->findAll($request, true);

        $custom_title = $request->input('t');

        return view("admin.screens.bookings.index", [
            'bookings' => $bookings,
            "payments" => $this->repository->getPaymentStats($bookings),
            'custom_title' => $custom_title
        ]);
    }

    public function show(Booking $booking) {
        $booking->load(['customer', 'payments', 'location']);
        return view("admin.screens.bookings.show", ['booking' => $booking]);
    }

    public function update(BookingRequest $request, Booking $booking) {
        $request->updateBooking($booking);
        if ($request->wantsJson()) {
            return new BookingResource($booking);
        }

        $request->session()->flash("success_message", "Updated booking");
        return redirect()->route("admin.bookings.show", $booking);
    }
}
