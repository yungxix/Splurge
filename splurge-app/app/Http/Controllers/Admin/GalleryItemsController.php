<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryItemRequest;
use App\Http\Resources\GalleryItemResource;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Support\ModelResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GalleryItemsController extends Controller
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
    public function create(Gallery $gallery, Request $request)
    {
        // $gallery->loadCount(['items', 'mediaItems']);
        $gallery_item = new GalleryItem(array_reduce(['heading', 'content'],
         fn ($carry, $key) => array_merge($carry, [$key => $request->old($key)]), []));
        return view('admin.screens.gallery.items.create', ['gallery' => $gallery, 'gallery_item' => $gallery_item]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GalleryItemRequest $request, Gallery $gallery)
    {
       $item = $request->createItem($gallery);
       if ($request->wantsJson()) {
           $item->load('mediaItems');
           return new GalleryItemResource($item);
       }
       $request->session()->flash('success_message', 'New gallery page created');
       return redirect()->route('admin.gallery_detail.gallery_items.show',
        ['gallery' => $gallery->id, 'gallery_item' => $item->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery, GalleryItem $galleryItem)
    {
        return view('admin.screens.gallery.items.show', ['gallery' => $gallery, 'gallery_item' => $galleryItem]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery, GalleryItem $gallery_item)
    {
        return view('admin.screens.gallery.items.edit',
         ['gallery' => $gallery, 'gallery_item' => $gallery_item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GalleryItemRequest $request)
    {
        $gallery_item = $request->updateItem(
            ModelResolver::fromRoute(
                $request->route('gallery_item'), GalleryItem::class));

        if ($request->wantsJson()) {
            $gallery_item->load('mediaItems');
            return new GalleryItemResource($gallery_item);
        }
        $request->session()->flash('success_message', 'Updated gallery page');
        return redirect()->route('admin.gallery_detail.gallery_items.show',
        ['gallery' => $gallery_item->gallery_id, 'gallery_item' => $gallery_item->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $gallery_item = ModelResolver::fromRoute($request->route('gallery_item'), GalleryItem::class);
        DB::transaction(fn () => $gallery_item->delete());
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Deleted page']);
        }
        $request->session()->flash('success_message', 'Deleted gallery page');
        return redirect()->route('admin.gallery.index',
        ['gallery' => $gallery_item->gallery_id]);
    }
}
