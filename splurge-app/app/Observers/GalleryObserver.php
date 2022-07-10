<?php

namespace App\Observers;

use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\MediaOwner;

class GalleryObserver
{
    /**
     * Handle the Gallery "created" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function created(Gallery $gallery)
    {
        //
    }

    /**
     * Handle the Gallery "updated" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function updated(Gallery $gallery)
    {
        //
    }

    /**
     * Handle the Gallery "deleted" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function deleted(Gallery $gallery)
    {
        MediaOwner::ownedBy(GalleryItem::class)
        ->whereExists(function ($query) use ($gallery) {
            $media_table = (new MediaOwner())->getTable();
            $gallery_item_table = (new GalleryItem())->getTable();
            return $query->selectRaw("1")
            ->from($gallery_item_table)
            ->whereColumn("{$gallery_item_table}.id", "=", "{$media_table}.owner_id")
            ->where("{$gallery_item_table}.gallery_id", "=", $gallery->id);
        })->delete();

        $gallery->items()->delete();
        $gallery->taggables()->delete();
    }

    /**
     * Handle the Gallery "restored" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function restored(Gallery $gallery)
    {
        //
    }

    /**
     * Handle the Gallery "force deleted" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function forceDeleted(Gallery $gallery)
    {
        //
    }
}
