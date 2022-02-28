<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\MediaOwner;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class GalleryRepository {
    public function findAll(Request $request) {
        $query = Gallery::with(['items' => function ($q) {
            $q->limit(6);
        }, 'items.mediaItems', 'taggables', 'taggables.tag']);
        if ($request->has('q')) {

            $term = sprintf('%%%s%%', $request->input('q'));

            $query = $query->where('caption', 'like', $term)
            ->orWhereHas('items', function (Builder $builder) use ($term) {
                return $builder->where('heading', 'like', $term);
            });
        } else {
            $query = $query->whereHas('items');
        }
        if ($request->has('tag')) {
            $query = $query->whereHas('taggables', function (Builder $builder) use ($request){
                return $builder->whereHas('tag', function (Builder $b2) use ($request) {
                    return $b2->where('name', 'like', sprintf('%%%s%%', $request->input('tag')));
                });
            });
        }
        return $query->simplePaginate($request->input('page_size', 3));
    }


    public function simpleFindAll(Request $request, $addStats = false, $includeAuthor = false) {
        $query = (new Gallery())->newQuery();

        if ($includeAuthor) {
            $query = $query->with(['author' => function ($authorQuery) {
                return $authorQuery->select('id', 'name');
            }]);
        }

        if ($addStats) {
            $query = $query->withCount(['items', 'mediaItems']);
        }

        if ($request->has('q')) {

            $term = sprintf('%%%s%%', $request->input('q'));

            $query = $query->where('caption', 'like', $term);
        } 

        if ($request->has('tag')) {
            $query = $query->whereHas('taggables', function (Builder $builder) use ($request){
                return $builder->whereHas('tag', function (Builder $b2) use ($request) {
                    return $b2->where('name', 'like', sprintf('%%%s%%', $request->input('tag')));
                });
            });
        }
        return $query->orderBy('created_at', 'desc')->paginate($request->input('page_size', 5));
    }

    public function find($id) {
        return Gallery::with(['items', 'items.mediaItems', 'taggables', 'taggables.tag'])->findOrFail($id);
    }

    public function getRecentMediaItems($limit = 6) {
        $lastDate = Carbon::now()->subMonths(2);
        return MediaOwner::with('owner')->forGalleryItems()
        ->where('created_at', '>=', $lastDate)
        ->where('media_type', 'image')
        ->orderBy('created_at', 'desc')->limit($limit)->get();
    }
}