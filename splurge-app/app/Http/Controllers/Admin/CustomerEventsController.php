<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerEventRequest;
use App\Http\Resources\CustomerEventGuestResource;
use App\Http\Resources\CustomerEventResource;
use App\Models\Address;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\CustomerEvent;
use App\Models\CustomerEventGuest;
use App\Repositories\CustomerEventRepository;
use Illuminate\Http\Request;
use App\Support\Builders\Table\TableBuilder;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use App\Models\Service;

class CustomerEventsController extends Controller
{

    private $repository;

    public function __construct(CustomerEventRepository $repository )
    {
        $this->repository  = $repository;
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
            ->with(['booking', 'booking.customer', 'booking.location'])
            ->orderBy(Arr::get($alt_column_names, $sorting[0], $sorting[0]), $sorting[1])
            ->paginate($request->input('page_size', 20));
            
        if ($request->wantsJson()) {
            return CustomerEventResource::collection($events);
        }


        return view('admin.screens.customer_events.index', ['table' => 
        static::eventsAsTable($events)
        ->useRequest($request)->withSortKey('sort')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $services = Service::with(['tiers'])->get();

        return view('admin.screens.customer_events.create', [
            'services' => $services,
             'customer_event' => static::getOldCustomerEvent($request)]);
    }

    private static function getSimpleKey(string $key): string {
        $idx = strrpos($key, '.');
        if ($idx === FALSE) {
            return $key;
        }
        return substr($key, $idx);
    }


    private static function getOldCustomerEvent(Request $request) {
        $data = [];
        foreach (['name', 'event_date'] as $key) {
            $data[$key] = $request->old("customer_event.$key");
        }
        $event = new CustomerEvent($data);
        $event->booking = static::getOldBooking($request);
        return $event;
    }

    private static function getOldBooking(Request $request) {
        $data = [];
        foreach (['booking.description', 'event_date', 'booking.service_tier_id'] as $key) {
            
            $data[static::getSimpleKey($key)] = $request->old($key);
        }
        $booking = new Booking($data);
        $booking->customer = static::getOldCustomer($request);
        $booking->location = static::getOldLocation($request);
        return $booking;
    }

    private static function getOldCustomer(Request $request) {
        $data = [];
        $customer_attributes = ['first_name', 'last_name', 'email', 'phone'];
        foreach ($customer_attributes as $key) {
            $data[$key] = $request->old("booking.customer.$key");
        }
        $customer = new Customer($data);
        return $customer;
    }

    private static function getOldLocation(Request $request) {
        $data = [];
        $customer_attributes = ['line1', 'line2', 'state'];
        foreach ($customer_attributes as $key) {
            $data[$key] = $request->old("booking.location.$key");
        }
        $address = new Address($data);
        return $address;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerEventRequest $request)
    {
        $event = $request->commitNew();
        if ($request->wantsJson()) {
            return new CustomerEventResource($event);
        }
        return redirect()->route('admin.customer_events.show', ['customer_event' => $event->id])
        ->with(['success_message' => "New customer event created"]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerEvent $event,  Request $request)
    {
        if ($request->wantsJson()) {
            return new CustomerEventResource($event);
        }
        $column_aliases = ['date' => 'attendance_at', 'attendance' => 'attendance_at', 'attended_at' => 'attendance_at'];

        $sort =  explode(' ', $request->input('sort', 'name asc'));
        
        $query_query = CustomerEventGuest::filteredBy($event->guests(), $request);
        
        $guests = $query_query->orderBy(
            Arr::get($column_aliases, $sort[0], $sort[0]),
             $sort[1])->paginate(15)->appends(['sort']);

        return view('admin.screens.customer_events.show',
         ['customer_event' => $event, 'guests' => $guests,
          'guest_table' => static::guestsAsTable($guests, $event)
          ->withSortKey('sort')
          ->useRequest($request)
          ->withCaption('Guests')
        ]);
    }

    private static function eventsAsTable($events) {
        return TableBuilder::ofSchema($events, [
            [
                'type' => 'link',
                'url' => function ($event) {
                    return route('admin.customer_events.show', ['customer_event' => $event->id]);
                },
                'text' => 'Name',
                'attributes' => ['class' => 'link', 'title' => 'Event details'],
                'attribute' => function ($event) {
                    return Str::limit($event->name, 50);
                }
            ], [
                'type' => 'text',
                'text' => 'Date',
                'attribute' => 'event_date',
                'formatter' => function ($date) {
                    if (is_string($date)) {
                        $date = Carbon::parse($date);
                    }
                    return sprintf('%s (%s)', $date->format('jS F, Y'),
                     $date->diffForHumans());
                },
                'sortKey' => 'date',
                'order' => 'desc'
            ], [
                'type' => 'text',
                'text' => 'Customer',
                'attributes' => ['class' => 'whitespace-nowrap'],
                'attribute' => function (CustomerEvent $event) {
                    if (is_null($event->booking)) {
                        return new HtmlString('<em>No associated customer record</em>');
                    }
                    $customer = $event->booking->customer;
                    return sprintf('%s %s', $customer->first_name, $customer->last_name);
                }
            ], [
                'type' => 'text',
                'text' => 'State',
                'attribute' => function (CustomerEvent $event) {
                    return Arr::get($event, 'booking.location.state', '') ?: '';
                }
            ], [
                'type' => 'template',
                'text' => '',
                'template' => 'admin.screens.customer_events.item-actions',
                'attribute' => function (CustomerEvent $event) {
                    return '';
                }
            ]
        ])
        ->paged()->withEmptyView('admin.screens.customer_events.empty');
    }

    public static function guestsAsTable($items, CustomerEvent $event) {
        $past = $event->isPast();
        return TableBuilder::ofSchema($items, [
            [
                'type' => 'link',
                'text' => 'Name',
                'url' => function (CustomerEventGuest $guest) use ($event) {
                    return route('admin.customer_event_detail.guests.show', ['customer_event' => $event->id, 'guest' => $guest->id]);
                },
                'sortKey' => 'name',
                'attribute' => function ($event) {
                    return Str::limit($event->name, 50);
                }
            ], [
                'type' => 'view',
                'view' => 'admin.screens.customer_events.guest-attachments',
                'view_arguments' => ['past' => $past, 'customer_event' => $event, 'attribute' => 'presented'],
                'text' => 'Presented'
            ], [
                'type' => 'view',
                'view' => 'admin.screens.customer_events.guest-attachments',
                'view_arguments' => ['past' => $past, 'customer_event' => $event, 'attribute' => 'accepted'],
                'text' => 'Accepted'
            ], [
                'type' => 'text',
                'text' => 'Attended',
                'view' => 'admin.screens.customer_events.guest-attendance',
                'template' => 'admin.screens.customer_events.guest-attendance',
                'view_arguments' => ['past' => $past, 'customer_event' => $event],
                'sortKey' => 'date'
            ], [
                'type' => 'view',
                'text' => '',
                'view' => 'admin.screens.customer_events.guest-actions',
                'view_arguments' => ['past' => $past, 'event' => $event],
            ]
        ])
        ->paged();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerEvent $customer_event)
    {
        $services = Service::with(['tiers'])->get();
        return view('admin.screens.customer_events.edit', ['customer_event' => $customer_event, 'services' => $services]);
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
        if ($request->wantsJson()) {
            return new CustomerEventResource($customer_event);
        }
        return redirect()->route('admin.customer_events.show', $customer_event)->with(['success_message' => 'Saved event changes']);
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
        
        return redirect()->route('admin.customer_events.index')->with(['success_message' => 'Deleted customer event']);
    }
}
