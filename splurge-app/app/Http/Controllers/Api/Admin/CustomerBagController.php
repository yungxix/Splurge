<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\GuestUserBagItemResource;
use App\Models\GuestUserBagItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class CustomerBagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $event, int $guest)
    {
        $data = GuestUserBagItem::where([
            'event_user_id' => $guest
        ])->orderBy('created_at', 'asc')->get();

        return GuestUserBagItemResource::collection($data);
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $event, int $guest)
    {
        $validated = $request->validate([
            'confirmed_by' => ['sometimes', 'nullable', 'max:30'],
            'confirmed_at' => ['sometimes', 'date'],
            'item_count' => ['required', 'integer', 'min:1'],
            'name' => ['required', 'max:255'],
            'item_type' => ['required', Rule::in(['SOUVENIR', "GIFT"])]
        ]);

        $model = GuestUserBagItem::create(array_merge($validated, ['event_user_id' => $guest]));

        return new GuestUserBagItemResource($model);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $event, int $guest, $id)
    {
        $model = GuestUserBagItem::where([
            'event_user_id' => $guest,
            'id' => $id
        ])->firstOrFail();
        return new GuestUserBagItemResource($model);
    }

  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request$request, int $event, int $guest, $id)
    {
        $validated = $request->validate([
            'confirmed_by' => ['sometimes', 'nullable', 'max:30'],
            'confirmed_at' => ['sometimes', 'date'],
            'item_count' => ['sometimes', 'integer', 'min:1'],
            'name' => ['required', 'max:255'],
            'item_type' => ['sometimes', Rule::in(['SOUVENIR', "GIFT"])]
        ]);
        $model = GuestUserBagItem::where([
            'event_user_id' => $guest,
            'id' => $id
        ])->firstOrFail();

        $model->update($validated);
        return new GuestUserBagItemResource($model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request$request, int $event, int $guest, $id)
    {
        $model = GuestUserBagItem::where([
            'event_user_id' => $guest,
            'id' => $id
        ])->firstOrFail();
        $model->delete();

        return response()->json(['message' => 'Bag item has been deleted']);
    }
}
