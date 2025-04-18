<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\SplurgeEvent;
class EventLocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $event)
    {
        $query = Address::where('addressable_type', SplurgeEvent::class)
            ->where('addressable_id', $event);

        if ($request->input('include_tables')) {
            $query = $query->with(['tables']);
        }
        foreach (['purpose', 'type'] as $type) {
            if ($request->input($type)) {
                $query = $query->where('purpose', $request->input($type));
                break;
            }
        }

        return AddressResource::collection($query->get());
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $event)
    {
        $validated = $request->validate([
            'line1' => ['required', 'max:255'],
            'line2' => ['sometimes', 'nullable', 'max:255'],
            'state' => ['required', 'max:30'],
            'name' => ['sometimes', 'nullable', 'max:255'],
            'zip' => ['sometimes', 'nullable', 'max:12'],
            'purpose' => ['required', 'max:25']
        ]);
        if (!isset($validated['purpose'])) {
            $validated['purpose'] = 'reception';
        }

        $address = Address::create(array_merge($validated, [
            'addressable_id' => $event,
            'addressable_type' => SplurgeEvent::class,
        ]));

        return new AddressResource($address);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $event, $id)
    {
        $address = Address::with(['addressable', 'tables'])->where([
            'addressable_type' => SplurgeEvent::class,
            'addressable_id' => $event,
            'id' => $id
        ])->firstOrFail();
        return new AddressResource($address);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $event, $id)
    {
        $validated = $request->validate([
            'line1' => ['sometimes', 'max:255'],
            'line2' => ['sometimes', 'nullable', 'max:255'],
            'state' => ['sometimes', 'max:30'],
            'name' => ['sometimes', 'nullable', 'max:255'],
            'zip' => ['sometimes', 'nullable', 'max:12'],
        ]);

        $address = Address::where([
            'addressable_id' => $event,
            'addressable_type' => SplurgeEvent::class,
            'id' => $id
        ])->firstOrFail();

        $address->update($validated);

        return new AddressResource($address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $event, $id)
    {
        $address = Address::where([
            'addressable_id' => $event,
            'addressable_type' => SplurgeEvent::class,
            'id' => $id
        ])->firstOrFail();
        if ($address->purpose == "BOOKING") {
            return response()->json(['message' => 'You cannot delete booking location'], 401);
        }
        $address->delete();
        return response()->json(['message' => 'Deleted location']);
    }
    
}
