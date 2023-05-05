<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerEvent;
use App\Models\MenuPreference;
use Illuminate\Http\Request;

class GuestMenuPreferencesController extends Controller
{
    public function index($eventId, $guestId) {
        $this->authorizeEvent($eventId, 'view');
        return MenuPreference::where('guest_id', $guestId)->get();
    }
    public function store(Request $request, $eventId, $guestId) {
        $this->authorizeEvent($eventId);

        $validated = $request->validate([
            'name' => 'required'
        ]);

        return MenuPreference::create([
            'name' => $validated['name'],
            'guest_id' => $guestId
        ]);

    }

    public function destroy(Request $request, $eventId, $guestId, $id) {
        $this->authorizeEvent($eventId);
        MenuPreference::where(['guest_id' => $guestId, 'id' => $id])->delete();
    }


    public function update(Request $request, $eventId, $guestId, $id) {
        $this->authorizeEvent($eventId);
        $validated = $request->validate([
            'name' => 'required'
        ]);
        $item = MenuPreference::where(['guest_id' => $guestId, 'id' => $id])->firstOrFail();

        $item->update(['name' => $validated['name']]);

        return $item;
        
    }

    private function authorizeEvent($id, $action = 'update') {
        $event = new CustomerEvent();
        $event->id = $id;
        $this->authorize($action ?? 'update', $event);
    }
}
