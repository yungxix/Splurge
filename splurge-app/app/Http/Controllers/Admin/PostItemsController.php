<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostItemRequest;
use App\Models\PostItem;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Post $post)
    {
        $post_item = new PostItem(['name' => $request->old('name'), 'content' => $request->old('content')]);
        return view('admin.screens.posts.items.create', ['post' => $post, 'post_item' => $post_item]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostItemRequest $request, Post $post)
    {
        $request->createItem($post);
        $request->session()->flash('success_message', 'Added a section to post');
        return redirect()->route('admin.posts.show', $post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post, PostItem $post_item)
    {
        return view('admin.screens.posts.items.edit', ['post' => $post, 'post_item' => $post_item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostItemRequest $request, Post $post, PostItem $post_item)
    {
        $request->updateItem($post_item);

        $request->session()->flash('success_message', 'Updated section');

        return redirect()->route('admin.posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post, PostItem $post_item)
    {
        DB::transaction(fn () => $post_item->delete());

        $request->session()->flash('success_message', 'Deleted section');

        return redirect()->route('admin.posts.show', $post);
    }
}
