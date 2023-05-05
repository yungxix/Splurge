<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = MenuItem::orderBy('created_at', 'desc')->get();
        if ($request->wantsJson()) {
            return MenuItemResource::collection($items);
        }
        return view('admin.screens.menu_items.index', ['items' => $items]);
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);
        $item = MenuItem::create(['name' => $request->input('name')]);
        if ($request->wantsJson()) {
            return new MenuItemResource($item);
        }
        return redirect()->route('admin.menu_items.index');
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
        $request->validate([
            'name' => 'required|max:255'
        ]);
        $item = MenuItem::findOrFail($id);
        $item->update(['name' => $request->input('name')]);

        if ($request->wantsJson()) {
            return new MenuItemResource($item);
        }
        return redirect()->route('admin.menu_items.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);
        $item->delete();
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Deleted']);
        }
        return redirect()->route('admin.menu_items.index')->with(['success_message' => 'Deleted menu item']);
    }
}
