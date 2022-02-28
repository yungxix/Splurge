<?php

namespace App\Search;

use App\Models\Post;
use Illuminate\Http\Request;

class PostSearchProvider {
    public function search(Request $request) {
        $tag = $request->input('tag');

        $posts = Post::search($request->input('q'))->tagged($tag);

        return view('partials.search.posts',
         ['posts' => $posts->orderBy('created_at', 'desc')
         ->paginate()->appends(['q','type', 'tag'])]);

    }

}