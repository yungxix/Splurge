<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateSplurgeEventUserRequest;
use App\Http\Requests\Api\UpdateSplurgeEventUserRequest;
use App\Http\Resources\SplurgeEventUserResource;
use App\Models\SplurgeEvent;
use App\Models\SplurgeEventUser;
use Illuminate\Http\Request;

class SplurgeEventUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $event)
    {
        $query = SplurgeEventUser::where('event_id', $event);

        if ($search = $request->input('q')) {
            $term = "%$search%";
            $query = $query->where(function ($inner) use ($term) {
                return $inner->where('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        }

        if ($tag = $request->input('tag')) {
            $query = $query->where('tag', $tag);
        }

        if (!$request->has('include_customer')) {
            $query = $query->where('role', '<>', 'CUSTOMER');
        }


        foreach (['from_date' => '>=', 'to_date' => '<='] as $param => $op) {
            if ($value = $request->input($param)) {
                $query = $query->where('created_at', $op, $value);
            }
        }


        return SplurgeEventUserResource::collection($query->paginate($request->input('page_size', 15)));
    }

    public function lookup(Request $request)
    {

        $user = SplurgeEventUser::with(['splurgeEvent', 'splurgeEvent.members' => function ($member) {
            return $member->where('role', 'CUSTOMER');
        }, 'tables', 'menuItems.menuItem', 'bagItems', 'splurgeEvent.locations' => function ($location) {
            return $location->where('purpose', 'BOOKING');
        }])
            ->where(['tag' => $request->input('tag')])->firstOrFail();
        return new SplurgeEventUserResource($user);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSplurgeEventUserRequest $request, int $event)
    {
        $eventModel = SplurgeEvent::findOrFail($event);

        $guest = $request->commit($eventModel);

        return new SplurgeEventUserResource($guest);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $event, $id)
    {
        $user = SplurgeEventUser::with(['splurgeEvent', 'splurgeEvent.members' => function ($member) {
            return $member->where('role', 'CUSTOMER');
        }, 'tables', 'menuItems.menuItem', 'bagItems', 'splurgeEvent.locations' => function ($location) {
            return $location->where('purpose', 'BOOKING');
        }])
            ->where(['event_id' => $event, 'id' => $id])->firstOrFail();
        return new SplurgeEventUserResource($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSplurgeEventUserRequest $request, int $event, $id)
    {
        $user = SplurgeEventUser::where(['event_id' => $event, 'id' => $id])->firstOrFail();
        $request->commit($user);
        return new SplurgeEventUserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $event, $id)
    {
        $user = SplurgeEventUser::where(['event_id' => $event, 'id' => $id])->firstOrFail();
        if ($user->role === "CUSTOMER") {
            return response()->json(['message' => 'You cannot delete a member of an event with customer role'], 401);
        }
        $user->delete();
        return response()->json(['message' => 'Guest has been deleted']);
    }
}
