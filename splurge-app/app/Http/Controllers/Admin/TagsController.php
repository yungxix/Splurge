<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagsController extends Controller
{
    private $repository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->repository = $tagRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('attachments')) {
            return TagResource::collection($this->repository->findAllWithAttachments($request));  
        }
        if ($request->wantsJson()) {
            return TagResource::collection($this->repository->findAll($request));  
        }
        return view('admin.screens.tags.index', ['tags' => $this->repository->findAll($request)]);
    }

    public function attach(Tag $tag, Request $request) {
        $validator = Validator::make($request->all(), [
            'taggable.id' => 'required|integer',
            'taggable.type' => 'required'
        ]);
        $validator->validate();

        $this->repository->attachTo($tag, $request->input('taggable'));

        return response()->json(['message' => 'Attached']);
    }

    public function detach(Tag $tag, Request $request) {
        $validator = Validator::make($request->all(), [
            'taggable.id' => 'required|integer',
            'taggable.type' => 'required'
        ]);
        $validator->validate();

        $this->repository->detachFrom($tag, $request->input('taggable'));

        return response()->json(['message' => 'Detached']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        $tag = $request->storeTag();
        if ($request->wantsJson()) {
            return new TagResource($tag);
        }
        $request->session()->flash('success_message', 'Stored new tag');
        return redirect()->route('admin.tags.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Tag $tag, Request $request)
    {
        $tag = $request->updateTag($tag);
        if ($request->wantsJson()) {
            return new TagResource($tag);
        }
        $request->session()->flash('success_message', 'Updated tag');
        return redirect()->route('admin.tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
