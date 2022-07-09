<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;

use App\Repositories\ServiceRepository;
use App\Models\Service;
use App\Models\ServiceTier;
use App\Repositories\SplurgeAccessTokenRepository;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    private $repo;
    private $accessRepo;
    public function __construct(ServiceRepository $repo, SplurgeAccessTokenRepository $accessRepo)
    {
        $this->repo = $repo;
        $this->accessRepo = $accessRepo;
    }
    
    public function create(Request  $request, Service $service) {
        foreach (['tier', 't'] as $tier_key) {
            if ($request->has($tier_key)) {
                return view('screens.book.create', 
                ['service' => $service,
                 'tier' => ServiceTier::findOrFail($request->input($tier_key))]);
            }
        }
        $service->load(["tiers" => function ($q) {
            $q->orderBy("position", "asc");
        }]);

        if ($service->tiers->count() === 1) {
            return view('screens.book.create', 
                ['service' => $service,
                 'tier' => $service->tiers->first()]);
        }

        return view('screens.book.select_tier', ['service' => $service]);
    }

    public function store(NewBookingRequest  $request) {
        $booking = $request->acceptBooking();
        if ($request->wantsJson()) {
            if ($booking->serviceTier->price > 0 && !$request->hasPayment()) {
                return new BookingResource($booking, $this->createVerificationCode($booking));
            }
            return new BookingResource($booking);
        }
        $request->session()->flash("success", "New booking recorded");
        $request->session()->flash("booking_created", "1");
        return redirect()->route("show_booking", $booking);
    }

    public function index(Request  $request) {
        $services = $this->repo->bookAbleServices();
        if ($services->count() == 1) {
            return redirect()->route('book_service', ['service' => $services->first()->id]);
        }
        return view('screens.book.index', ['services' => $services]);
    }

    public function show(Booking $booking) {

    }

    private function createVerificationCode(Booking $booking) {
        return $this->accessRepo->createForUser($booking, 0, Carbon::now()->addHours(3))->token;
    }
}
