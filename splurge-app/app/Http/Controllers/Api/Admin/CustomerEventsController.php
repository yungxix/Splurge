<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateSplurgeEventRequest;
use App\Http\Requests\Api\UpdateSplurgeEventRequest;
use App\Http\Resources\SplurgeEventResource;
use App\Models\SplurgeEvent;
use Illuminate\Http\Request;

class CustomerEventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = (new SplurgeEvent())->newQuery();
        if (!$request->has('lean')) {
            $query = $query->with([
                'members' => function ($query) {
                    return $query->where('role', 'CUSTOMER');
                },
                'serviceTier'
            ]);
        }

        if (!empty($request->input('q'))) {
            $query = $query->where('name', 'like', "%{$request->input('q')}%");
        }

        $date_columns = ['from_date' => '>=', 'to_date' => '<='];

        foreach ($date_columns as $col => $op) {
            if (!empty($request->input($col))) {
                $query = $query->where('event_date', $op, $request->input($col));
            }
        }

        $sort = explode(' ', $request->input('sort', 'event_date desc'));


        $query = $query->orderBy($sort[0], $sort[1]);


        $data = $query->paginate($request->input('page_size', 15));


        return SplurgeEventResource::collection($data);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(CreateSplurgeEventRequest $request)
    {
        $event = $request->commit();
        return new SplurgeEventResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $event = SplurgeEvent::with([
            'members' => function ($query) {
                return $query->where('role', 'CUSTOMER');
            },
            'locations',
            'serviceTier'
        ])->findOrFail($id);
        return new SplurgeEventResource($event);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(UpdateSplurgeEventRequest $request, $id)
    {
        $event = SplurgeEvent::findOrFail($id);
        $request->commit($event);
        return new SplurgeEventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $event = SplurgeEvent::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Event has been deleted']);
    }
}
