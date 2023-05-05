<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventTableRequest;
use App\Http\Resources\EventTableResource;
use Illuminate\Http\Request;
use App\Models\EventTable;
use App\Repositories\EventTablesRepository;
use App\Models\CustomerEvent;

class EventTablesController extends Controller
{
    private $repository;

    public function __construct(EventTablesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $eventId)
    {
        if ($request->has('available')) {
            return EventTableResource::collection($this->repository->findAllAvailable($request, $eventId));
        }

        return EventTableResource::collection($this->repository->findAll($eventId));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventTableRequest $request, $eventId)
    {
        $event = CustomerEvent::findOrFail($eventId);
        $request->commitNew($event);
        return EventTableResource::collection($event->eventTables()->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventTableRequest $request, $eventId, $id)
    {
        $model = EventTable::findOrFail($id);
        $request->commitUpdate($model);
        return new EventTableResource($model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $eventId, $id)
    {
        $model = EventTable::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Deleted table information']);
    }
}
