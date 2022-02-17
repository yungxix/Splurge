<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    private $repository;
    public function __construct(PostRepository $repo)
    {
        $this->repository = $repo;
    }

    public function index(Request $request) {
        return view('screens.events.index' , 
        ['eventType' => 'any',
         'posts' => $this->repository->findAll($request)]);
    }

    public function ofType(Request $request, $event_type) {
        return view('screens.events.index',
         [
             'eventType' => Str::plural(Str::upper($event_type)),
             'posts' => $this->repository->findAll($request, Str::singular($event_type))
        ]);
    }

    public function show(Request $request, Post $post) {
        $post->load(['items', 'taggables', 'taggables.tag']);
        return view('screens.events.show', ['post' => $post]);
    }
}
