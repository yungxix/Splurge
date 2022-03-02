<?php

namespace App\Repositories;

use App\Models\Gallery;
use App\Models\Service;
use App\Models\Post;
use Illuminate\Support\Carbon;
use App\Models\GalleryItem;

use App\Models\PostItem;

use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Arr;

use Illuminate\Support\Str;

class StatsRepository
{
    public function loadDashboardStats()
    {
        return Cache::remember('dashboard_stats_v4', 60 * 2, function () {
            $cut_off = Carbon::now()->subMonths(2)->endOfMonth();

            $message = sprintf('Uploaded within %s',  Str::of($cut_off->diffForHumans())->replace(' ago', ''));

            $gallery1 = GalleryItem::where('created_at', '>', $cut_off)
                ->selectRaw("count(*) as record_count, 'Gallery' as section,  '$message' as title");

            $message2 = sprintf('Uploaded later after %s', Str::of($cut_off->diffForHumans())->replace(' ago', ''));

            $gallery2 = GalleryItem::where('created_at', '<=', $cut_off)
                ->selectRaw("count(*) as record_count, 'Gallery' as section,  '$message2' as title");

            $message = sprintf('Posted within %s', Str::of($cut_off->diffForHumans())->replace(' ago', ''));

            $posts1 = Post::where('created_at', '>', $cut_off)
                ->selectRaw("count(*) as record_count, 'Events/Posts' as section,  '$message' as title");

            $message = sprintf('Posted later after %s', Str::of($cut_off->diffForHumans())->replace(' ago', ''));

            $posts2 = Post::where('created_at', '<=', $cut_off)
                ->selectRaw("count(*) as record_count, 'Events/Posts' as section,  '$message' as title");


            $services = Service::selectRaw('count(*) as record_count, "Services" as section, "Number of services advertised"');

            $counts = $gallery1
                ->union($gallery2)
                ->union($posts1)
                ->union($posts2)
                ->union($services)
                ->get();


            $dates1 = GalleryItem::selectRaw('max(created_at) as date_value, "Gallery" as section, "Last gallery content upload" as title');
            $dates2 = PostItem::selectRaw('max(created_at) as date_value, "Event/Post" as section, "Last published event item" as title');


            return [
                'counts' => $counts,
                'dates' => $dates1->union($dates2)->get()->map(function ($record) {
                    return array_merge(Arr::except($record->toArray(), 'date_value'), [
                        'date_value' => Carbon::parse($record['date_value'])
                    ]);
                })
            ];
        });
    }
}
