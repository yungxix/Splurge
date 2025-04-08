<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerEventGuestImportRequest;
use Illuminate\Http\Request;
use App\Models\CustomerEvent;
use App\Models\CustomerEventGuest;
use App\Http\Resources\CustomerEventGuestResource;
use App\Http\Requests\CustomerEventGuestRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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

        $this->authorize('view', $event);

        $column_aliases = ['date' => 'created_at', 'attendance' => 'attendance_at', 'attended_at' => 'attendance_at'];

        $sort =  explode(' ', $request->input('sort', 'name asc'));

        $query_query = CustomerEventGuest::filteredBy($event->guests(), $request);
        
        $guests = $query_query->orderBy(
            Arr::get($column_aliases, $sort[0], $sort[0]),
             $sort[1])->paginate(15)->appends(['sort']);

        return CustomerEventGuestResource::collection($guests);
    }

    private function requireVerificationRole(Request $request) {
        if (Gate::denies('verify-guests')) {
            return abort(422);
        }
    }

    public function lookup(Request $request, $eventId) {    
        $this->requireVerificationRole($request);
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
        $this->requireVerificationRole($request);
        $guest = CustomerEventGuest::byTag($request->input('tag'))->firstOrFail();
        if ($request->has('lean')) {
            return new CustomerEventGuestResource($guest);
        }
        return new CustomerEventGuestResource($guest->loadMissing(['customerEvent', 'menuPreferences',
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
        $this->authorize('update', $event);
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

    public function handleImport(CustomerEventGuestImportRequest $request, $eventId) {
        $event = CustomerEvent::findOrFail($eventId);
        $this->authorize('update', $event);
        $affected = $request->store($event);
        return response()->json(["message" => "Affected $affected", 'affected' => $affected, 'status' => 'OK']);
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
        $this->authorize('update', $event);
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
        $guest = CustomerEventGuest::where('customer_event_id', $eventId)->findOrFail($guestId);
        $this->authorizeEvent($eventId);
        $guest->delete();
        return response()->json(['message' => 'Deleted guest']);
    }

    private function authorizeEvent($id) {
        $event = new CustomerEvent();
        $event->id = $id;
        $this->authorize('update', $event);
    }

    public function updateBarcode(Request $request, $eventId, $guestId) {
        $guest = CustomerEventGuest::where('customer_event_id', $eventId)->findOrFail($guestId);
        $this->authorizeEvent($eventId);
        $guest->generateBarcode(TRUE);
        return response()->json(['url' => $guest->barcode_image_url]);
    }

    public function addMenuItem(Request $request, $eventId, $guestId) {
        $guest = CustomerEventGuest::where(['customer_event_id' => $eventId, 'id' => $guestId])->firstOrFail();
        $this->authorizeEvent($eventId);
        $request->validate([
            'item.name' => 'required|max:255',
            'item.comment' => 'nullable|max:255'
        ]);
        $guest->menuPreferences()->create($request->only(['item.name', 'item.comment']));
        
        return new CustomerEventGuestResource($guest->loadMissing(['menuPreferences']));
    }

    public function setMenuItems(Request $request, $eventId, $guestId) {
        $guest = CustomerEventGuest::where(['id' => $guestId, 'customer_event_id' => $eventId])->firstOrFail();
        $this->authorizeEvent($eventId);
        $request->validate([
            'items' => 'required|array',
            'items.name' => 'required|max:255',
            'items.comment' => 'nullable|max:255',
        ]);
        DB::transaction(function () use ($guest, $request) {
            $guest->menuPreferences()->delete();
            $guest->menuPreferences()->createMany($request->input('items'));
        });
        return new CustomerEventGuestResource($guest->loadMissing(['menuPreferences']));
    }

    public function removeMenuItem(Request $request, $eventId, $guestId) {
        $request->validate([
            'item.name' => 'required'
        ]);
        $guest = CustomerEventGuest::where(['id' => $guestId, 'customer_event_id' => $eventId])->firstOrFail();
        $this->authorizeEvent($eventId);
        // $event = CustomerEvent::findOrFail($eventId);
        $guest->menuPreferences()->where('name', 'like', $request->input('item.name'))->delete();
        $guest = $guest->loadMissing(['menuPreferences']);
        return new CustomerEventGuestResource($guest);
    }

    
}
