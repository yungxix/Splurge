<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignedVenueTableResource;
use App\Models\AssignedVenueTable;
use App\Models\SplurgeEvent;
use App\Models\VenueTable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SplurgeEventUserTablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $event, $location, $guest = 0)
    {
        $query = AssignedVenueTable::with(['guest', 'venueTable'])
        ->whereHas('guest', function ($guestQuery) use ($event) {
            return $guestQuery->where('event_id', $event);
        })->whereHas('venueTable', function ($table) use ($location) {
            return $table->where('address_id', $location);
        });

        if ($guest) {
            $query = $query->where('event_user_id', $guest);
        }

        if ($request->has('table_ids')) {
            $query = $query->whereIn('table_id', explode(',', $request->input('table_ids')));
        }
        if ($table = $request->input('table_id')) {
            $query = $query->where('table_id', $table);
        }
        if ($guests = $request->input('guest_ids')) {
            $query = $query->whereIn('event_user_id', explode(',', $guests));
        }
        return AssignedVenueTableResource::collection($query->get());
    }


    public function assignAll(Request $request, $event, $location) {
        $this->authorize('admin');
        $validated = $request->validate([
            'table_id' => ['required', 'integer'],
            'guest_ids' => ['required', 'array']
        ]);
        AssignedVenueTable::whereHas('guest', function ($guest) use ($event) {
            return $guest->where('event_id', $event);
        })->whereIn('event_user_id', $validated['guest_ids'])
        ->whereHas('venueTable', function ($table) use ($location) {
            return $table->where('address_id', $location);
        })
        ->delete();
        $table = VenueTable::where(['address_id' => $location, 'id' => $validated['table_id']])
                ->whereHas('location', function ($address) use ($event) {
                    return $address->where([
                        'addressable_id' => $event,
                        'addressable_type' => SplurgeEvent::class
                    ]);
                })->firstOrFail();
        $models = $table->assignments()->createMany(Arr::map($validated['guest_ids'], function ($id) {
            return ['event_user_id' => $id];
        }));

        return AssignedVenueTableResource::collection($models);
    }

    public function unassignAll(Request $request, $event, $location)
    {
        $this->authorize('admin');
        
        $affected = AssignedVenueTable::whereHas('guest', function ($guest) use ($event) {
            return $guest->where('event_id', $event);
        })
        ->whereHas('venueTable', function ($table) use ($location) {
            return $table->where('address_id', $location);
        })->where('table_id', $request->input('table_id'))
            ->delete();

        return response()->json(['message' => "$affected record(s) were affected"]);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $event, $location)
    {
        $this->authorize('admin');
        $validated = $request->validate([
            'table_id' => ['required', 'integer'],
            'guest_id' => ['required', 'integer']
        ]);

        AssignedVenueTable::whereHas('guest', function ($guest) use ($event) {
            return $guest->where('event_id', $event);
        })->where('event_user_id', $validated['guest_id'])
        ->whereHas('venueTable', function ($table) use ($location) {
            return $table->where('address_id', $location);
        })
        ->delete();

        $table = VenueTable::where(['address_id' => $location, 'id' => $validated['table_id']])
        ->whereHas('location', function ($address) use ($event) {
            return $address->where([
                'addressable_id' => $event,
                'addressable_type' => SplurgeEvent::class
            ]);
        })->firstOrFail();

        $model = $table->assignments()->create(['event_user_id', $validated['guest_id']]);

        return new AssignedVenueTableResource($model);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $event, $location, $id)
    {
        $model = AssignedVenueTable::with(['guest', 'venueTable'])->whereHas('guest', function ($guest) use ($event) {
            return $guest->where('event_id', $event);
        })
        ->whereHas('venueTable', function ($table) use ($location) {
            return $table->where('address_id', $location);
        })
        ->firstOrFail();
        return new AssignedVenueTableResource($model);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return response()->json(['message' => 'Not supported'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $event, $location, $id)
    {
        $model = AssignedVenueTable::whereHas('guest', function ($guest) use ($event) {
            return $guest->where('event_id', $event);
        })
        ->whereHas('venueTable', function ($table) use ($location) {
            return $table->where('address_id', $location);
        })->where('id', $id)->firsOrFail();

        $model->delete();

        return response()->json(['message' => 'Table assignment has been deleted']);
    }
}
