<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Repositories\ServiceRepository;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $serviceRepo;
    public function __construct(ServiceRepository $serviceRepo)
    {
        $this->serviceRepo = $serviceRepo;
    }

    public function redirectForRole(Request $request) {
        if ($request->user()->can("admin")) {
            return redirect()->route("admin.admin_dashboard");
        }
        return redirect()->to("/dashboard");
    }
    public function index(Request $request) {
        if (!empty($request->input('q'))) {
            return $this->getSearch($request);
        }
        return view('screens.welcome');
    }

    public function showDashboard() {
        return view('screens.dashboard');
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
        return view('screens.book.create', ['service' => $service]);
    }

    public function getBooking(Request  $request) {

        return view('screens.book.index', ['services' => $this->serviceRepo->all(true)]);
    }

}
