<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediumRequest;
use App\Http\Resources\MediaOwnerResource;
use App\Models\MediaOwner;
use App\Support\ModelResolver;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.screens.media.index');
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
    public function store(MediumRequest $request)
    {
        $medium = $request->store();
        
        if ($request->wantsJson()) {
            return new  MediaOwnerResource($medium);
        }
        $request->session()->flash('success_message', sprintf('Uploaded "%s" successfully', $medium->name));
        if ($request->has('redir')) {
            return redirect()->to($request->input('redir'));
        }
        return redirect()->back();
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $m =  ModelResolver::fromRoute($request->route('medium'), MediaOwner::class);
        $m->delete();
        if ($request->wantsJson()) {
            return response()->json(['messge' => 'Deleted']);
        }
        $request->session()->flash('success_message', 'Medium has been deleted');
        return redirect()->back();
    }
}