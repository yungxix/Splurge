<?php

namespace App\Providers;

use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\PostItem;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Service;
use App\Observers\GalleryItemObserver;
use App\Observers\GalleryObserver;
use App\Observers\PostItemObserver;
use App\Observers\PostObserver;
use App\Observers\ServiceObserver;
use App\Observers\TagObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $observers = [
        Gallery::class => [GalleryObserver::class],
        GalleryItem::class => [GalleryItemObserver::class],
        Post::class => [PostObserver::class],
        PostItem::class => [PostItemObserver::class],
        Tag::class => [TagObserver::class],
        Service::class => [ServiceObserver::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
