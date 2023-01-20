<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerEvent;
use App\Models\CustomerEventGuest;
use App\Http\Resources\CustomerEventGuestResource;
use App\Http\Requests\CustomerEventGuestRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CustomerEventGuestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $eventId)
    {
        $event = CustomerEvent::findOrFail($eventId);

        $column_aliases = ['date' => 'created_at', 'attendance' => 'attendance_at', 'attended_at' => 'attendance_at'];

        $sort =  explode(' ', $request->input('sort', 'name asc'));

        $query_query = CustomerEventGuest::filteredBy($event->guests(), $request);
        
        $guests = $query_query->orderBy(
            Arr::get($column_aliases, $sort[0], $sort[0]),
             $sort[1])->paginate(15)->appends(['sort']);

        return CustomerEventGuestResource::collection($guests);
    }

    public function lookup(Request $request, $eventId) {
        $guest = CustomerEventGuest::where(['customer_event_id' => $eventId, 'tag' => $request->input('tag')])
                    ->firstOrFail();
                    
        if ($request->has('lean')) {
            return new CustomerEventGuestResource($guest);
        }

        return new CustomerEventGuestResource($guest->loadMissing(['customerEvent',
        'customerEvent.booking',
         'customerEvent.booking.customer',
          'customerEvent.booking.location']));
    }

    public function lookupAny(Request $request) {
        $guest = CustomerEventGuest::byTag($request->input('tag'))->firstOrFail();
        if ($request->has('lean')) {
            return new CustomerEventGuestResource($guest);
        }
        return new CustomerEventGuestResource($guest->loadMissing(['customerEvent',
        'customerEvent.booking',
         'customerEvent.booking.customer',
          'customerEvent.booking.location']));
    }



    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerEventGuestRequest $request, $eventId)
    {
        $event = CustomerEvent::findOrFail($eventId);
        $guest = $request->commitNew($event);
        return new CustomerEventGuestResource($guest);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $eventId, $guestId)
    {
        $guest = CustomerEventGuest::findOrFail($guestId);
        $guest = $guest->loadMissing(['menuPreferences',
         'customerEvent',
          'customerEvent.booking',
           'customerEvent.booking.customer',
            'customerEvent.booking.location']);
        return new CustomerEventGuestResource($guest);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerEventGuestRequest $request, $eventId, $guestId)
    {
        $guest = CustomerEventGuest::findOrFail($guestId);
        $event = CustomerEvent::findOrFail($eventId);
        $data = $request->commitEdit($guest, $event);
        return new CustomerEventGuestResource($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $eventId, $guestId)
    {
        $guest = CustomerEventGuest::findOrFail($guestId);
        $guest->delete();
        return response()->json(['message' => 'Deleted guest']);
    }

    public function updateBarcode(Request $request, $eventId, $guestId) {
        $guest = CustomerEventGuest::findOrFail($guestId);
        // $event = CustomerEvent::findOrFail($eventId);
        $guest->generateBarcode(TRUE);
        return response()->json(['url' => $guest->barcode_image_url]);
    }

    public function addMenuItem(Request $request, $eventId, $guestId) {
        $guest = CustomerEventGuest::findOrFail($guestId);
        // $event = CustomerEvent::findOrFail($eventId);
        $request->validate([
            'item.name' => 'required|max:255',
            'item.comment' => 'nullable|max:255'
        ]);
        $guest->menuPreferences()->create($request->only(['item.name', 'item.comment']));
        
        return new CustomerEventGuestResource($guest->loadMissing(['menuPreferences']));
    }

    public function setMenuItems(Request $request, $eventId, $guestId) {
        $guest = CustomerEventGuest::findOrFail($guestId);
        // $event = CustomerEvent::findOrFail($eventId);
        $request->validate([
            'items' => 'required|array',
            'items.name' => 'required|max:255',
            'items.comment' => 'nullable|max:255',
        ]);
        return DB::transaction(function () use ($guest, $request) {
            $guest->menuPreferences()->delete();
            $guest->menuPreferences()->createMany($request->input('items'));
        });
    }

    public function removeMenuItem(Request $request, $eventId, $guestId) {
        $request->validate([
            'item.name' => 'required'
        ]);
        $guest = CustomerEventGuest::findOrFail($guestId);
        // $event = CustomerEvent::findOrFail($eventId);
        $guest->menuPreferences()->where('name', 'like', $request->input('item.name'))->delete();
        $guest = $guest->loadMissing(['menuPreferences']);
        return new CustomerEventGuestResource($guest);
    }

    
}
