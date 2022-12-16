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
    public function index(CustomerEvent $event, Request $request)
    {
        $column_aliases = ['date' => 'created_at', 'attendance' => 'attendance_at', 'attended_at' => 'attendance_at'];

        $sort =  explode(' ', $request->input('sort', 'name asc'));

        $query_query = CustomerEventGuest::filteredBy($event->guests(), $request);
        
        $guests = $query_query->orderBy(
            Arr::get($column_aliases, $sort[0], $sort[0]),
             $sort[1])->paginate(15)->appends(['sort']);

        return CustomerEventGuestResource::collection($guests);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerEvent $event, CustomerEventGuestRequest $request)
    {
        $guest = $request->commitNew($event);
        return new CustomerEventGuestResource($guest);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerEvent $event, CustomerEventGuest $guest)
    {
        $guest->loadMissing(['menu_preferences', 'customerEvent']);
        return new CustomerEventGuestResource($guest);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerEventGuestRequest $request, CustomerEvent $event, CustomerEventGuest $guest)
    {
        $data = $request->commitEdit($guest, $event);
        return new CustomerEventGuestResource($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CustomerEvent $event, CustomerEventGuest $guest)
    {
        $guest->delete();
        return response()->json(['message' => 'Deleted guest']);
    }

    public function updateBarcode(Request $request, CustomerEvent $event, CustomerEventGuest $guest) {
        $guest->generateBarcode(TRUE);
        return response()->json(['url' => $guest->barcode_image_url]);
    }

    public function addMenuItem(Request $request, CustomerEvent $event, CustomerEventGuest $guest) {
        $request->validate([
            'name' => 'required|max:255',
            'comment' => 'nullable|max:255'
        ]);
        $guest->menuPreferences()->create($request->only(['name', 'comment']));
        $guest->loadMissing(['menuPreferences']);
        return new CustomerEventGuestResource($guest);
    }

    public function setMenuItems(Request $request, CustomerEvent $event, CustomerEventGuest $guest) {
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

    public function removeMenuItem(Request $request, CustomerEvent $event, CustomerEventGuest $guest) {
        $request->validate([
            'name' => 'required'
        ]);
        $guest->menuPreferences()->where('name', 'like', $request->input('name'))->delete();
        $guest->loadMissing(['menuPreferences']);
        return new CustomerEventGuestResource($guest);
    }

    
}
