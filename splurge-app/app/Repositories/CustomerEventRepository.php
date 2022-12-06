<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\CustomerEvent;

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

        foreach (['from_date' => '>=', 'to_date' => '<='] as $optr => $attr) {
            $val = $request->input($attr);
            if (!empty($val)) {
                $query = $query->where('event_date', $optr, $val);
            }
        }
        return $query;
    }
}