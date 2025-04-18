<?php
    namespace App\Http\Controllers\Api\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\GuestMenuItemResource;
    use App\Models\GuestMenuItem;
    use App\Models\SplurgeEventUser;
    use Illuminate\Http\Request;

    class CustomerMenuItemsController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request, int  $event, int $guest = 0)
        {
            $items = GuestMenuItem::with(['menuItem'])->whereHas('eventUser', function ($query) use ($event) {
                return $query->where('event_id', $event);
            });
            if ($guest) {
                $items = $items->where('event_user_id', $guest);
            }
            return GuestMenuItemResource::collection($items->get());
        }


        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request, int  $event, int $guest)
        {
            $validated = $request->validate([
                'menu_item_id' => ['required', 'integer']
            ]);

            $user = SplurgeEventUser::where([
                'event_id' => $event,
                'id' => $guest
            ])->firstOrFail();

            $item =  $user->menuItems()->create($validated);

            return new GuestMenuItemResource($item);
        }

        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show(Request $request, int  $event, int $guest, $id)
        {
            $item = GuestMenuItem::with(['menuItem'])
                ->where(['event_user_id' => $guest, 'id' => $id])
                ->whereHas('eventUser', function ($user) use ($event) {
                    return $user->where('event_id', $event);
                })
                ->firstOrFail();

            return new GuestMenuItemResource($item);
        }



        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, int  $event, int $guest, $id)
        {
            $validated = $request->validate([
                'menu_item_id' => ['required', 'integer']
            ]);
            $item = GuestMenuItem::where(['event_user_id' => $guest, 'id' => $id])
                ->whereHas('eventUser', function ($user) use ($event) {
                    return $user->where('event_id', $event);
                })
                ->firstOrFail();
            $item->update($validated);
            return new GuestMenuItemResource($item);
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy(Request $request, int  $event, int $guest, $id)
        {
            $item = GuestMenuItem::where(['event_user_id' => $guest, 'id' => $id])
                ->whereHas('eventUser', function ($user) use ($event) {
                    return $user->where('event_id', $event);
                })
                ->firstOrFail();
            $item->delete();


            return response()->json(['message' => 'Menu item assignment has been deleted']);
        }
    }
