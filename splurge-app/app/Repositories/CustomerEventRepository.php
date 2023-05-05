<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\CustomerEvent;
use App\Models\CustomerEventGuest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerEventRepository {
    public function findAll(Request $request) {
        $query = CustomerEvent::withCount('guests');
        if ($request->has('booking')) {
            $query = $query->where('booking_id', $request->input('booking'));
        }
        $term = $request->input('q');
        if (!empty($term)) {
            $query = $query->where('name', 'like', "%$term%");   
        }

        foreach (['from_date' => '>=', 'to_date' => '<='] as $attr => $optr) {
            $val = $request->input($attr);
            if (!empty($val)) {
                $query = $query->where('event_date', $optr, $val);
            }
        }
        return $query;
    }

    public function getSingleEventStats($eventId) {
        return DB::table((new CustomerEventGuest())->getTable())
                ->where('customer_event_id', $eventId)
                ->groupBy('table_name', 'gender')
                ->selectRaw(implode(', ', [
                   'table_name', 'gender',
                    'count(id) as guest_count'
                    , 'min(created_at) as first_registered_date',
                     'max(created_at) as last_registered_date',
                      'sum(person_count) total_number_of_persons'
                ]))
                ->get();
    }

    public function getStats(Request $request) {
        $date = Carbon::parse($request->input('pivot_date', Carbon::now()->toIso8601String()));

        $monthCount = intval($request->input('gap', '6'));

        $upcomming = $date->addMonths($monthCount);

        $guest_table = (new CustomerEventGuest())->getTable();

        $event_table = (new CustomerEvent())->getTable();

        

        $query1 = static::queryForStats($event_table, $guest_table, [$date, $upcomming], "Upcoming events within $monthCount months");

        $query3 = static::queryForStats($event_table, $guest_table, [$date->subMonths($monthCount), $date->subDay(1)], "Past events within the last $monthCount months", '>');

        $query2 = static::queryForStats($event_table, $guest_table, $upcomming, "Upcoming events after $monthCount months", '>');

        return $query1->unionAll($query2)->unionAll($query3)->get();

    }

    private static function queryForStats(string $event_table, string $guest_table, $date, string $description, $date_operator = '=') {
        $query = is_array($date) ? DB::table($event_table)->whereBetween('event_date', $date) :
                                    DB::table($event_table)->where('event_date', $date_operator, $date);
        return $query->leftJoin($guest_table, "{$guest_table}.customer_event_id", '=', "{$event_table}.id")
                    ->selectRaw(implode(', ', [
                        "COUNT(distinct {$event_table}.id) as number_of_events",
                        "COUNT(distinct {$guest_table}.id) as number_of_guests",
                        "MAX({$event_table}.event_date) as latest_event_date",
                        "MIN({$event_table}.event_date) as earliest_event_date", 
                        "'$description' as description"
                    ]));


    }


}