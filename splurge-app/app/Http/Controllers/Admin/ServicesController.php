<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{
    private $repository;

    public function __construct(ServiceRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.screens.services.index', ['services' => $this->repository->allForAdmin()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $service = new Service([
            'name' => $request->old('name', ''),
            'description' => $request->old('description', ''),
            'display' => $request->old('display', 'default')
        ]);
        return view('admin.screens.services.create', ['service' => $service]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        $service = $request->saveItem();
        $request->session()->flash('success_message', 'Saved new service');
        return redirect()->to(route('admin.services.show', $service));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return view('admin.screens.services.show', ['service' => $service]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        return view('admin.screens.services.edit', ['service' => $service]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $request->updateItem($service);
        $request->session()->flash('success_message', 'Updated service');
        return redirect()->to(route('admin.services.show', $service));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Service $service)
    {
        DB::transaction(fn () => $service->delete());
        $request->session()->flash('success_message', 'Service has been deleted');
        return redirect()->route('admin.services.index');
    }
}
