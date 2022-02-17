<?php
namespace App\Repositories;


use Illuminate\Support\Facades\Cache;
use App\Models\Service;

class ServiceRepository {

    public function __construct()
    {
        
    }

    public function forWidget() {
        return Cache::remember('widgets.services', 120, function () {
            return Service::orderBy('created_at', 'desc')->limit(6)->get();
        });
    }

    public function all() {
        return Service::orderBy('created_at', 'desc')->get();
    }
}