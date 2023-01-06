<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CustomerEventRepository;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerEventResource;
use Illuminate\Support\Arr;
use App\Models\CustomerEvent;
use App\Http\Requests\CustomerEventRequest;

class CustomerEventsController extends Controller
{

    private $repository;

    public function __construct(CustomerEventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getStats(Request $request) {
        $data = $this->repository->getStats($request);
        return response()->json($data->toArray());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sorting =  explode(' ', $request->input('sort', 'date desc'));
        $alt_column_names = ['date' => 'event_date', 'created' => 'created_at', 'updated' => 'updated_at'];

        $events = $this->repository->findAll($request)
            ->orderBy(Arr::get($alt_column_names, $sorting[0], $sorting[0]), $sorting[1])
            ->paginate($request->input('page_size', 20));
            
        return CustomerEventResource::collection($events);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerEventRequest $request)
    {
        $customer_event = $request->commitNew();
        return new CustomerEventResource($customer_event);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CustomerEvent $event)
    {
        $event = $event->loadMissing(['booking', 'booking.customer', 'booking.location', 'booking.serviceTier']);
        return new CustomerEventResource($event);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerEventRequest $request, CustomerEvent $customer_event)
    {
        $request->commitEdit($customer_event);

        // $customer_event->loadMissing(['booking', 'booking.customer', 'booking.location']);

        return new CustomerEventResource($customer_event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerEvent $customer_event)
    {
        $customer_event->delete();
        return response()->json(['message' => 'Deleted event']);
    }
}
