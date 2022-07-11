<?php

namespace App\Http\Controllers;

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
    
    public function index(Request $request) {
        return view('screens.gallery.index', ['galleries' => $this->repository->findAll($request)]);
    }

    public function show(Gallery $gallery) {
        $gallery->load(['items', 'items.mediaItems']);
        return view('screens.gallery.show', ['gallery' => $gallery]);
    }
}
