<?php

namespace App\Repositories;


use App\Models\EventTable;

use App\Models\CustomerEventGuest;
use Illuminate\Http\Request;

class EventTablesRepository {


    public function findAll($eventId) {
        $query = EventTable::where('customer_event_id', $eventId);
        return $query->get();
    }

    public function findAllAvailable(Request $request, $eventId) {
        $query = EventTable::where('customer_event_id', $eventId);

        if (!empty($request->input('q'))) {
            $query  = $query->where('name', 'like', sprintf('%%%s%%', $request->input('q')));
        }

        if ($request->has('limit')) {
            $query = $query->orderBy('name', 'asc')->take($request->input('limit'));
        }

        $assignments = CustomerEventGuest::where('customer_event_id', $eventId)
                            ->whereNot(function ($q) {
                                return $q->whereNull('table_name')->orWhere('table_name', '');
                            })
                            ->selectRaw('table_name, sum(person_count) as total_person_count')
                            ->groupBy('table_name')
                            ->get();

        return $query->get()->filter(function ($table, $k) use ($assignments) {
            if ($assignments->isEmpty()) {
                return true;
            }

            $assignment = $assignments->first(fn ($q, $i) => strnatcasecmp($q->table_name, $table->name) === 0);

            if (is_null($assignment)) {
                $table->setAvailable($table->capactity);
                return true;
            }

            $table->setAvailable($table->capacity - $assignment->total_person_count);

            return $assignment->total_person_count < $table->capacity;

        });

    }

}
