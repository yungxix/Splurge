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
            return Service::available()->orderBy('created_at', 'desc')->limit(6)->get();
        });
    }

    public function all($withItems = false) {
        if ($withItems) {
            return Service::with('tiers')->available()->orderBy('created_at', 'desc')->get();    
        }
        return Service::available()->orderBy('created_at', 'desc')->get();
    }

    public function forMenu() {        
        return Service::where('display', 'menu')->select('id', 'name')->orderBy('created_at', 'desc')->get();
    }

    public function allForAdmin($withItems = false) {
        if ($withItems) {
            return Service::with('tiers')->orderBy('created_at', 'desc')->get();    
        }
        return Service::orderBy('created_at', 'desc')->get();
    }

    public function bookAbleServices() {
        return Service::has("tiers")->available()->orderBy("created_at", "desc")->get();
    }
     
}