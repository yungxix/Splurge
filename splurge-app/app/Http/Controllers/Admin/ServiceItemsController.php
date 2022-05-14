<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceItemRequest;
use App\Http\Resources\ServiceItemResource;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Support\Facades\DB;

class ServiceItemsController extends Controller
{
    public function store(ServiceItemRequest $request, Service $service) {
        $item = $request->addToService($service);
        if ($request->wantsJson()) {
            return new ServiceItemResource($item);
        }
        $request->session()->flash('success_message', 'Added service item');
        return redirect()->route('admin.services.show', $service);
    }


    public function index(Request $request, Service $service) {
        if ($request->wantsJson()) {
            return ServiceItemResource::collection($service->items);
        }
        return view('admin.screens.services.items.index', ['service' => $service]);
    }


    public function update(ServiceItemRequest $request, Service $service, ServiceItem $serviceItem) {
        $item = $request->updateItem($serviceItem);
        if ($request->wantsJson()) {
            return new ServiceItemResource($item);
        }
        $request->session()->flash('success_message', 'Updated service item');
        return redirect()->route('admin.services.show', $service);
    }

    public function sort(Request $request, Service $service) {
        DB::transaction(function () use ($request, $service) {
            $ids = $request->input('ids');
            foreach ($ids as $index => $id) {
                ServiceItem::where(['service_id' => $service->id, 'id' => $id])
                ->update(['sort_number' => $index + 1]);
            }
        });
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Sorted']);
        }
        $request->session()->flash('success_message', 'Sorted items');
        return redirect()->route('admin.services.show', $service);
    }


    public function destroy(Request $request, Service $service, ServiceItem $serviceItem) {
        $serviceItem->delete();
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Deleted service item']);
        }
        $request->session()->flash('success_message', 'Deleted service item');
        return redirect()->route('admin.services.show', $service);
    }
}
