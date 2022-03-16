<?php

namespace App\Observers;

use App\Models\MediaOwner;
use App\Models\Post;
use App\Models\PostItem;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        //
    }

    /**
     * Handle the Post "updated" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
       
    }

    /**
     * Handle the Post "deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        MediaOwner::ownedBy(PostItem::class)
        ->whereExists(function ($query) use ($post) {
            $media_table = (new MediaOwner())->getTable();
            $post_item_table = (new PostItem())->getTable();
            return $query->selectRaw("1")
            ->from($post_item_table)
            ->whereColumn("{$post_item_table}.id", "=", "{$media_table}.owner_id")
            ->where("{$post_item_table}.post_id", "=", $post->id);
        })->delete();

        $post->items()->delete();
        $post->taggables()->delete();
    }

    /**
     * Handle the Post "restored" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function restored(Post $post)
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        //
    }
}
