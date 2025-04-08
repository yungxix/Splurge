<?php

namespace App\Providers;

use App\Events\BookingPriceChangedEvent;
use App\Events\NewBookingCreatedEvent;
use App\Events\PaymentCreatedEvent;
use App\Listeners\BookingPriceChangedListener;
use App\Listeners\NewBookingCreatedHandler;
use App\Listeners\PaymentCreatedHandler;
// use App\Models\Booking;
use App\Models\CustomerEvent;
use App\Models\CustomerEventGuest;
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
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\LogRegisteredUser',
        ],
     
        'Illuminate\Auth\Events\Attempting' => [
            'App\Listeners\LogAuthenticationAttempt',
        ],
     
        'Illuminate\Auth\Events\Authenticated' => [
            'App\Listeners\LogAuthenticated',
        ],
     
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
     
        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\LogFailedLogin',
        ],
     
        'Illuminate\Auth\Events\Validated' => [
            'App\Listeners\LogValidated',
        ],
     
        'Illuminate\Auth\Events\Verified' => [
            'App\Listeners\LogVerified',
        ],
     
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogSuccessfulLogout',
        ],
     
        'Illuminate\Auth\Events\CurrentDeviceLogout' => [
            'App\Listeners\LogCurrentDeviceLogout',
        ],
     
        'Illuminate\Auth\Events\OtherDeviceLogout' => [
            'App\Listeners\LogOtherDeviceLogout',
        ],
     
        'Illuminate\Auth\Events\Lockout' => [
            'App\Listeners\LogLockout',
        ],
     
        'Illuminate\Auth\Events\PasswordReset' => [
            'App\Listeners\LogPasswordReset',
        ],

        
        PaymentCreatedEvent::class => [
            PaymentCreatedHandler::class
        ],
        'Illuminate\Mail\Events\MessageSending' => [
            'App\Listeners\LogSendingMessage',
        ],
        'Illuminate\Mail\Events\MessageSent' => [
            'App\Listeners\LogSentMessage',
        ]
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
