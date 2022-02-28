<?php

namespace App\Http\Controllers;

use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;
use App\Models\Service;

class ServicesController extends Controller
{
    private $repository;
    public function __construct(ServiceRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request) {
        return view('screens.services.index',
         ['services' => $this->repository->all()]);
    }

    public function show(Request $request, Service $service) {
        return view('screens.services.show', ['service' => $service]);
    }
}
