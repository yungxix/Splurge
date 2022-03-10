<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Repositories\ServiceRepository;
use App\Repositories\StatsRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $statsRepo;
    private $serviceRepo;
    public function __construct(StatsRepository $statsRepo, ServiceRepository $serviceRepo)
    {
        $this->statsRepo = $statsRepo;
        $this->serviceRepo = $serviceRepo;
    }
    public function index() {
        return view('screens.welcome');
    }

    public function showDashboard() {
        return view('screens.dashboard', ['stats' => $this->statsRepo->loadDashboardStats()]);
    }

    public function getSearch(Request $request) {
        $title = $request->input('q');
        $tag = $request->input('ti');
        return view('screens.search', ['title' => $title, 'tag' => $tag]);
    }


    public function getTaggedSearch(Request $request) {
        $type = $request->input('type');
        $provider = app("{$type}_search_provider");
        return $provider->search($request);
    }

    public function getBookingForService(Request  $request, Service $service) {
        return view('screens.welcome');
    }

    public function getBooking(Request  $request, Service $service) {
        return view('screens.welcome');
    }

}
