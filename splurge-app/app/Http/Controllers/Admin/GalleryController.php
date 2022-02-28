<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Repositories\GalleryRepository;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    private $repository;

    public function __construct(GalleryRepository $repo)
    {
        $this->repository = $repo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.screens.gallery.index',
         ['gallery' => $this->repository->simpleFindAll($request, true, true)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $attributes = [];
        foreach (['caption', 'description'] as $key) {
            $attributes[$key] = $request->old($key);
        }
        $gallery = new Gallery($attributes);

        return view('admin.screens.gallery.create', ['gallery' => $gallery]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GalleryRequest $request)
    {
        $g = $request->createGallery();
        $request->session()->flash('success_message', 'Gallery has been created');
        return redirect()->route('admin.gallery.show', ['gallery' => $g->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
        $items = $gallery->items()->withCount('mediaItems')->orderBy('created_at',  'asc')->simplePaginate();
        // $itemsTable->setData($gallery->items);
        // $itemsTable->addColumn(LinkTableColumn::of(['attribute' => 'heading', 'url' => function ($item) use ($gallery) {
        //     return route('admin.gallery_detail.gallery_items.show', ['gallery' => $gallery->id, 'gallery_item' => $item->id]);
        // }]));
        // $itemsTable->addColumn([
        //     'text' => '',
        //     'template' => 'admin.screens.gallery.partials.items.table-actions'
        // ]);
        return view('admin.screens.gallery.show', ['gallery' => $gallery, 'items' => $items]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        return view('admin.screens.gallery.edit', ['gallery' => $gallery]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\GalleryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GalleryRequest $request, Gallery $gallery)
    {
        $request->updateGallery($gallery);
        
        $request->session()->flash('success_message', 'Gallery updated');
        return redirect()->route('admin.gallery.show', ['gallery' => $gallery->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Gallery $item)
    {
        $item->delete();
        $request->session()->flash('success_message', 'Gallery has been deleted');
        return redirect()->route('admin.gallery.index');
    }
}
