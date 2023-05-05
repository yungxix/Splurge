<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerEventGuestRequest;
use App\Http\Requests\CustomerEventGuestImportRequest;
use App\Http\Resources\CustomerEventGuestResource;
use App\Models\CustomerEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\CustomerEventGuest;

class CustomerEventGuestsAdminController extends Controller
{
    public function getPrintView(CustomerEvent $event) {
        $guests = $event->guests()->paginate(100);
        return view('admin.screens.customer_events.guests.print', ['guests' => $guests, 'customer_event' => $event]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CustomerEvent $event, Request $request)
    {
        $this->authorize('view', $event);
        
        $column_aliases = ['date' => 'attendance_at', 'attendance' => 'attendance_at', 'attended_at' => 'attendance_at'];

        $sort =  explode(' ', $request->input('sort', 'name asc'));

        $query_query = CustomerEventGuest::filteredBy($event->guests(), $request);
        
        $guests = $query_query->orderBy(
            Arr::get($column_aliases, $sort[0], $sort[0]),
             $sort[1])->paginate(15)->appends(['sort']);

        if ($request->wantsJson()) {
            return CustomerEventGuestResource::collection($guests);
        }

        return view('admin.screens.customer_events.show', [
            'customer_event' => $event,
            'guests' => $guests,
             'guest_table' =>
              CustomerEventsController::guestsAsTable(
                $guests, $event)
                ->withSortKey('sort')
                ->useRequest($request)
                ->withCaption('Guests')]);
    }

    public function handleImport(CustomerEventGuestImportRequest $request, CustomerEvent $cutomer_event) {
        $this->authorize('update', $cutomer_event);
        $affected = $request->store($cutomer_event);
        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Imported $affected",
                'affected' => $affected
            ]);
        }
        return redirect()->back()->with(['success_message' => "Affected $affected"]);
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CustomerEvent $event, Request $request)
    {
        $this->authorize('update', $event);
        return view('admin.screens.customer_events.guests.create',
         ['customer_event' => $event, 'guest' => static::getOldGuest($request)]);
    }

    private static function getOldGuest(Request $request) {
        $attributes = ['name', 'gender', 'table_name'];
        $data = [];
        foreach ($attributes as $key) {
            $data[$key] = $request->input("guest.$key");
        }
        return new CustomerEventGuest($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerEvent $event, CustomerEventGuestRequest $request)
    {
        $this->authorize('update', $event);
        $guest = $request->commitNew($event);
        if ($request->wantsJson()) {
            return new CustomerEventGuestResource($guest);
        }
        return redirect()->route('admin.customer_events.show', $event)->with(['success_message' => 'New guest has been registed']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerEvent $event, CustomerEventGuest $guest)
    {
        $this->authorize('update', $event);
        return view('admin.screens.customer_events.guests.show', ['guest' => $guest, 'customer_event' => $event]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerEvent $event, CustomerEventGuest $guest)
    {
        $this->authorize('update', $event);
        return view('admin.screens.customer_events.guests.edit', ['guest' => $guest, 'customer_event' => $event]);
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
        $this->authorize('update', $event);
        $data = $request->commitEdit($guest, $event);
        if ($request->wantsJson()) {
            return new CustomerEventGuestResource($data);
        }
        if ($request->has('rdir')) {
            $redirect = $request->input('rdir');
            if ('back' === $redirect) {
                return redirect()->back()->with(['success_message' => 'Guest updated']);
            }
            return redirect()->to($redirect)->with(['success_message' => 'Guest updated']);
        }
        return redirect()->route('admin.customer_event_detail.guests.show',
         ['customer_event' => $event->id, 'guest' => $guest->id])->with(['success_message' => 'Updated guest']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CustomerEvent $event, CustomerEventGuest $guest)
    {
        $this->authorize('update', $event);
        $guest->delete();
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Deleted guest']);
        }
        return redirect()->route('admin.customer_events.show', $event)->with(['success_message' => 'Deleted guest']);
    }

    public function updateBarcode(Request $request, CustomerEvent $event, CustomerEventGuest $guest) {
        $this->authorize('update', $event);
        $guest->generateBarcode(TRUE);
        if ($request->wantsJson()) {
            return response()->json(['url' => $guest->barcode_image_url]);
        }
        return redirect()->route('admin.customer_events.show', $event)->with(['success_message' => 'Barcode generated']);
    }

}
