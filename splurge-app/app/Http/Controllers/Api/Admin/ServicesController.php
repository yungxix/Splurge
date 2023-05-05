<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index() {
        return ServiceResource::collection(Service::with(['tiers'])->get());
    }
}
