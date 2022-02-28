<?php

namespace App\Search;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GallerySearchProvider {
    public function search(Request $request) {
        $tag = $request->input('tag');

        $gallery = Gallery::search($request->input('q'))->orWhereHas('items', function ($query) use ($request) {
            return $query->search($request->input('q'));
        })->tagged($tag);

        return view('partials.search.gallery',
         ['gallery' => $gallery->orderBy('created_at', 'desc')
         ->paginate()->appends(['q','type', 'tag'])]);

    }

}