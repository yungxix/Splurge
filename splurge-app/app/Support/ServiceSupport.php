<?php

namespace App\Support;

use App\Models\Service;
use App\Models\ServiceItem;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ServiceSupport {
    public static function getEstimateMinimumPrice(Service $service) {
        return self::getTotalAmount($service, 'min', fn ($c, $k) => true);
    }

    public static function hideServicesMenuItem() {
        $records = Service::select("display", DB::raw("count(*) record_count"))->groupBy('display')->get();
        return  $records->every(fn ($item, $k) => 'menu' === $item->display);
    }




    private static function getAmount(ServiceItem $item, Service $service, $side = 'default') {
        switch ($item->pricing_type) {
            case 'increment':
            case 'incremental':
                $mul = 'max' === $side ? Arr::get($item->options, 'maximum', 0) : Arr::get($item->options, 'minimum', 0);
                return $mul * $item->price;
            case 'percentage':
            case 'percent':
                $rate = Arr::get($item->options, 'rate', 0);
                if ($rate === 0) {
                    return 0;
                }
                $base = Arr::get($item->options, 'base', 'default');
                $total = self::getTotalAmount($service, $side, function ($candidate, $key) use ($base) {
                    return !preg_match('/percent/', $candidate->pricing_type) && $candidate->category == $base;
                });
                return ($rate/100) * $total;
            default:
                if ($item->required || 'max' === $side) {
                    return $item->price;
                }
                return 0;
                
                

                
        }
    }

    private static function getTotalAmount(Service $service, $side, Closure $filter) {
        return $service->items->filter($filter)->sum(fn ($item) => self::getAmount($item, $service, $side));
    }
}