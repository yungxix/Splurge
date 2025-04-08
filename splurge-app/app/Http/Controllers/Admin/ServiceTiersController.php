<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceTierPositionRequest;
use App\Http\Requests\ServiceTierRequest;
use App\Http\Resources\ServiceTierResource;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ServiceTier;

class ServiceTiersController extends Controller
{
    public function index(Request $request, Service $service) {
        if ($request->wantsJson()) {
            return ServiceTierResource::collection($service->tiers);
        }
        return view('admin.screens.services.tiers.index', ['service' => $service, 
        'tiers' => $service->tiers()->orderBy("position", "asc")->get()]);
    }

    public function create(Request $request, Service $service) {
        $tier = new ServiceTier([
            'name' => $request->old('name'),
            'description' => $request->old('description'),
            'options' => $request->old('options', []),
            'footer_message' => $request->old('footer_message'),
            'price' => $request->old('price')
        ]);

        return view('admin.screens.services.tiers.create', ['service' => $service, 'tier' => $tier]);
    }

    public function edit(Request $request, Service $service, ServiceTier $tier) {
        return view('admin.screens.services.tiers.edit', ['service' => $service, 'tier' => $tier]);
    }
     

    public function store(ServiceTierRequest $request, Service $service) {
        $tier = $request->createItem($service);
        if ($request->wantsJson()) {
            return new ServiceTierResource($tier);
        } 
        $request->session()->flash("success_message", "New service tier saved");
        return redirect()->route('admin.service_detail.tiers.index', ['service' => $service->id]);
    }


    public function update(ServiceTierRequest $request, Service $service, ServiceTier $tier) {
        $request->updateItem($tier);
        if ($request->wantsJson()) {
            return new ServiceTierResource($tier);
        } 
        $request->session()->flash("success_message", "Updated service tier");
        return redirect()->route('admin.service_detail.tiers.index', ['service' => $service->id]);
    }

    public function updatePosition(ServiceTierPositionRequest $request, Service $service, ServiceTier $tier) {
        $request->applyTo($tier);
        if ($request->wantsJson()) {
            return new ServiceTierResource($tier);
        } 
        $request->session()->flash("success_message", "Positions have been updated service tier");
        return redirect()->route('admin.service_detail.tiers.index', ['service' => $service->id]);
    }

    public function destroy(Request $request, Service $service, ServiceTier $tier) {
        if ($this->inUse($tier)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => sprintf('"%s" has already been booked', $tier->name)], 402);
            }
            $request->session()->flash("error_message", sprintf('"%s" has already been booked', $tier->name));
            return redirect()->back();
        }
        $tier->delete();
        if ($request->wantsJson()) {
            return response()->json(['message' =>  'Deleted', 'status' => 'OK']);
        }
        $request->session()->flash("success_message", "Deleted service tier");
        return redirect()->route('admin.service_detail.tiers.index', ['service' => $service->id]);
    }


    public function show(Service $service, ServiceTier $tier) {
        return view("admin.screens.services.tiers.show", ['service' => $service, 'tier' => $tier]);
    }

    private function inUse(ServiceTier $tier) {
        throw new \ErrorException("Not implement yet", 500);
        // return !is_null(Booking::where("service_tier_id", $tier->id)
        //     ->selectRaw("1")->first());
    }

}
