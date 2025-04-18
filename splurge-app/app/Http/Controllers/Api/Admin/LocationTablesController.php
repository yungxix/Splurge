<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignedVenueTableResource;
use App\Http\Resources\VenueTableResource;
use App\Models\AssignedVenueTable;
use App\Models\SplurgeEvent;
use App\Models\VenueTable;
use Illuminate\Http\Request;

class LocationTablesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $event, int $location)
    {
        $models = VenueTable::where([
            'address_id' => $location
        ])->whereHas('location', function ($query) use ($event) {
            return $query->where([
                'addressable_id' => $event,
                'addressable_type' => SplurgeEvent::class
            ]);
        })->get();
        return VenueTableResource::collection($models);
    }

   
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $event, int $location)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'capacity' => ['sometimes', 'integer', 'min:1']
        ]);

        $model = VenueTable::create(array_merge($validated, [
            'address_id' => $location
        ]));

        return new VenueTableResource($model);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $event, int $location, $id)
    {
        $model = VenueTable::with(['assignments'])->where(['id' => $id, 'address_id' => $location])
            ->whereHas('location', function ($address) use ($event) {
            return $address->where(['addressable_id' => $event, 'addressable_type' => SplurgeEvent::class]);
        })->firsOrFail();
        
        return new VenueTableResource($model);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $event, int $location, $id)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'max:255'],
            'capacity' => ['sometimes', 'integer', 'min:1']
        ]);
        $model = VenueTable::where(['id' => $id, 'address_id' => $location])
            ->whereHas('location', function ($address) use ($event) {
                return $address->where(['addressable_id' => $event, 'addressable_type' => SplurgeEvent::class]);
            })->firsOrFail();
        $model->update($validated);

        return new VenueTableResource($model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $event, int $location, $id)
    {
        $model = VenueTable::where(['id' => $id, 'address_id' => $location])
            ->whereHas('location', function ($address) use ($event) {
                return $address->where(['addressable_id' => $event, 'addressable_type' => SplurgeEvent::class]);
            })->firsOrFail();
        $model->delete();
        return response()->json(['message' => 'Table has been deleted']);
    }
}
