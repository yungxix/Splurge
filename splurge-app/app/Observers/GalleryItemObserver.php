<?php

namespace App\Observers;

use App\Models\GalleryItem;
use App\Models\MediaOwner;

class GalleryItemObserver
{
    /**
     * Handle the GalleryItem "created" event.
     *
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return void
     */
    public function created(GalleryItem $galleryItem)
    {
        //
    }

    /**
     * Handle the GalleryItem "updated" event.
     *
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return void
     */
    public function updated(GalleryItem $galleryItem)
    {
        //
    }

    /**
     * Handle the GalleryItem "deleted" event.
     *
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return void
     */
    public function deleted(GalleryItem $galleryItem)
    {
        MediaOwner::where(['owner_type' => GalleryItem::class, 'owner_id' => $galleryItem->id])->delete();
    }

    /**
     * Handle the GalleryItem "restored" event.
     *
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return void
     */
    public function restored(GalleryItem $galleryItem)
    {
        //
    }

    /**
     * Handle the GalleryItem "force deleted" event.
     *
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return void
     */
    public function forceDeleted(GalleryItem $galleryItem)
    {
        //
    }
}
