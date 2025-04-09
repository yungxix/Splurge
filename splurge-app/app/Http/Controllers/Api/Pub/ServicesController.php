<?php

namespace App\Http\Controllers\Api\Pub;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(Request $request) {
        return ServiceResource::collection(Service::with(['tiers'])->get());
    }
}
