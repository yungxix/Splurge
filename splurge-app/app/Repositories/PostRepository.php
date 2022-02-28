<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PostRepository {
    public function forWidget() {
        return Cache::remember('widgets.posts2', 120, function () {
            return Post::orderBy('created_at', 'desc')->limit(3)->get();
        });
    }

    public function forWidgetExcept($postId, $limit = 4) {
        return Post::where("id", "<>", $postId)->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    public function findAll(Request $request, $tag = NULL, $options = []) {
        $posts = (new Post())->newQuery();
        if (Arr::get($options, 'load_authors', false)) {
            $posts = $posts->with('author');
        }
        if (Arr::get($options, 'load_items_count', false)) {
            $posts = $posts->withCount('items');
        }
        if (!is_null($tag)) {
            $posts = $posts->whereHas('taggables', function ($query) use ($tag) {
                return $query->whereHas('tag', function ($q) use ($tag) {
                    return $q->where('name', 'like', "%$tag%");
                });
            });
        }
        if ($request->has('q')) {
            $term = sprintf('%%%s%%', $request->input('q'));
            $posts = $posts->where('name', 'like', $term)->orWhereHas('items', function ($query) use ($term) {
                return $query->where('name', 'like', $term);
            });
        }
        return $posts->orderBy('created_at', 'desc')->simplePaginate();
    }

}