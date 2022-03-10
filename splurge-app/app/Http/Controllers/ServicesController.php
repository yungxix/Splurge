<?php

namespace App\Http\Controllers;

use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Repositories\GalleryRepository;
use App\Repositories\PostRepository;

class ServicesController extends Controller
{
    private $repository;
    private $galleryRepo;
    private $postsRepo;
    public function __construct(ServiceRepository $repository, GalleryRepository $galleryRepo, PostRepository $postsRepo)
    {
        $this->repository = $repository;
        $this->galleryRepo = $galleryRepo;
        $this->postsRepo = $postsRepo;
    }
    public function index(Request $request) {
        return view('screens.services.index',
         ['services' => $this->repository->all()]);
    }

    public function show(Request $request, Service $service) {
        if ($request->has('events')) {
            return $this->openServiceAttachments($request, $service);
        }
        return view('screens.services.show', ['service' => $service]);
    }


    private function openServiceAttachments(Request $request, Service $service) {
        $tags = $service->taggables()->pluck('tag_id')->toArray();
        if (empty($tags)) {
            return view('screens.services.show', ['service' => $service]);
        }
        $gallery = $this->galleryRepo->findForTags($request, $tags, 'gallery_page');
        $posts = $this->postsRepo->findForTags($request, $tags, 'event_page');
        if ($gallery->isEmpty() && $posts->isEmpty()) {
            return view('screens.services.show', ['service' => $service]);
        }

        return view('screens.services.events', ['gallery' => $gallery, 'posts' => $posts, 'service' => $service]);


    }
}
