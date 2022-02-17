<?php

namespace App\View\Components\Widgets;

use App\Repositories\GalleryRepository;
use Illuminate\View\Component;

class RecentGallery extends Component
{   /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $repo = app()->make(GalleryRepository::class);
        return view('components.widgets.recent-gallery',
         ['items' => $repo->getRecentMediaItems()->map(function ($item) {
             return [
                 'image_url' => asset( $item->url),
                 'caption' => $item->owner->heading
             ];
         })]);
    }
}
