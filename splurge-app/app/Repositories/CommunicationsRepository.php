<?php

namespace App\Repositories;

use App\Models\Communication;
use Exception;
use Illuminate\Http\Request;


class CommunicationsRepository {
    public function findAll(Request $request) {
        $query = (new Communication())->newQuery();
        if (!empty($request->input("channel"))) {
            $query = $query->where("channel_type", "like", sprintf('%%%s', $request->input("channel")));
        }
        if (!empty($request->input("min_date"))) {
            $query = $query->where("created_at", ">=", $request->input("min_date"));
        }

        if (!empty($request->input("max_date"))) {
            $query = $query->where("created_at", "<=", $request->input("max_date"));
        }
        if (!empty($request->input("q"))) {
            $search = sprintf("%%%s%%", $request->input("q"));
            $query = $query->where("subject", "like", $search);
        }

        return $query;
    }

    public function dispatch($mailName, Request $request) {
        switch ($mailName) {
            case 'price_changed':
                return $this->dispatchBookingPriceChanged($request);
            default:
                throw new Exception("Unsupported mailer: $mailName");
        }
    }


    private function dispatchBookingPriceChanged(Request $request) {

    }
}