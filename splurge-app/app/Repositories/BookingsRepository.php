<?php
namespace App\Repositories;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use App\Models\SplurgeAccessToken;

class BookingsRepository {
    public function findAll(Request $request, $paginate = false) {
        $query = Booking::with(['customer', 'serviceTier', 'serviceTier.service']);

        $search = $request->input("q");

        if (!empty($search)) {
            $term = sprintf("%%%s%%", $search);
            $query = $query->where("description", "like", $term)
            ->orWhere("code", "like", $term)
            ->orWhereHas("customer", function ($q) use ($term) {
                $q->where("first_name", "like", $term)->orWhere("last_name", "like", $term);
            });

        }
        $date_filter_prefixes = ["date" => "event_date", "created" => "created_at", "posted" => "created_at"];
        $date_opts = ["min_" => ">=", "max_" => "<="];
        $date_queries = [];
        foreach ($date_opts as $prex => $operator) {
            foreach ($date_filter_prefixes as $query_part => $model_attribute) {
                $query_name = sprintf("%s%s", $prex, $query_part);
                if (!is_null($request->input($query_name))) {
                    $date_queries[] = $query_name;
                    $query = $query->where($model_attribute, $operator, $request->input($query_name));
                }
            }
        }
        if (($customer_id = $request->input("customer"))) {
            $query = $query->where("customer_id", $customer_id);
        }
        if (($state = $request->input("state"))) {
            $query = $query->whereHas("location", function ($q) use ($state) {
                $q->where("state", "like", $state);
            });
        }

        if ($request->input("paid", "0") == "1") {
            $query = $query->has("payments");
        }

        if ($paginate) {
            $order = $request->input("order", $request->input("sort"));
            if (empty($order)) {
                $order = "event_date desc";
            }
            $order = explode(" ", $order);
            return $query->orderBy(Arr::get($date_filter_prefixes, $order[0], $order[0]), count($order) > 1 ? $order[1] : "desc")
                        ->paginate($request->input("page_size", 10))
                        ->appends($request->only(array_merge(['order', "sort", 'state', 'q'], $date_queries)));
        }

        return $query;
    }


    public function findAllForUser(Request $request) {
        $userId = $request->user()->id;
        $customerTable = (new Customer())->getTable();
        $bookingTable = (new Booking())->getTable();

        $query = Booking::with(['serviceTier', 'serviceTier.service'])->join($customerTable, "${bookingTable}.customer_id", "=", "${customerTable}.id");

        $query = $query->whereHas("accessTokens", function ($tokens) use ($userId) {
            $tokens->where("user_id", $userId);
        })->orWhereHas("customer", function ($customer) use ($userId) {
            $customer->where("user_id", $userId);
        });

        return $query;

    }


    public function getPaymentStats($bookings) {
        $ids = $bookings->map(fn ($b, $k) => $b->id)->toArray();

        $payments = Payment::whereIn("booking_id", $ids)
                        ->groupBy("booking_id")
                        ->selectRaw("booking_id, SUM(amount) as total_paid")->get();


        $stats = $bookings->map(function ($booking) use ($payments) {
            $payment  = $payments->first(fn ($p) => $p->booking_id == $booking->id);

            return [
                'charge' => $booking->current_charge,
                'paid' => is_null($payment) ? 0 : $payment->total_paid,
                'booking_id' => $booking->id
            ];
        }); 

        return $stats;

    }

    public function getRecentBookings($limit = 5) {
        $query = Booking::whereBetween("event_date",
         [Carbon::now()->subDays(7), Carbon::now()->subHour(1)])
         ->orderBy("event_date", "desc");

        if ($limit > 0) {
            $query = $query->take($limit);
        }

        return $query->get();
        
    }

    public function createRecentBookingsUrl() {
        $query = Arr::query([
            'min_date' => Carbon::now()->subDays(7)->format('Y-m-d'),
            'max_date' => Carbon::now()->subHour(1)->format('Y-m-d'),
            'sort' => 'date desc',
            't' => 'Recent Bookings'
        ]);
        return route('admin.bookings.index') . '?' . $query;

    }

    public function getFutureBookings($limit = 5) {
        $query = Booking::where("event_date", ">=", Carbon::now())
         ->orderBy("event_date", "asc");

        if ($limit > 0) {
            $query = $query->take($limit);
        }

        return $query->get();
        
    }


    public function createFutureBookingsUrl() {
        $query = Arr::query([
            'min_date' => Carbon::now()->format('Y-m-d'),
            'sort' => 'date asc',
            't' => 'Upcoming Bookings'
        ]);
        return route('admin.bookings.index') . '?' . $query;

    }
}