<?php

namespace App\Observers;

use App\Models\PostItem;

class PostItemObserver
{
    /**
     * Handle the PostItem "created" event.
     *
     * @param  \App\Models\PostItem  $postItem
     * @return void
     */
    public function created(PostItem $postItem)
    {
        //
    }

    /**
     * Handle the PostItem "updated" event.
     *
     * @param  \App\Models\PostItem  $postItem
     * @return void
     */
    public function updated(PostItem $postItem)
    {
        //
    }

    /**
     * Handle the PostItem "deleted" event.
     *
     * @param  \App\Models\PostItem  $postItem
     * @return void
     */
    public function deleted(PostItem $postItem)
    {
        $postItem->mediaItems()->delete();
    }

    /**
     * Handle the PostItem "restored" event.
     *
     * @param  \App\Models\PostItem  $postItem
     * @return void
     */
    public function restored(PostItem $postItem)
    {
        //
    }

    /**
     * Handle the PostItem "force deleted" event.
     *
     * @param  \App\Models\PostItem  $postItem
     * @return void
     */
    public function forceDeleted(PostItem $postItem)
    {
        //
    }
}
