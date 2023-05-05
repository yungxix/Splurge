<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GalleryItemsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\PostItemsController;
use App\Http\Controllers\Admin\ServiceItemsController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\Admin\ServiceTiersController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\BookingsController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\CommunicationsController;
use App\Http\Controllers\Admin\CustomerEventGuestsAdminController;
use App\Http\Controllers\Admin\CustomerEventsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\MenuItemsController;

Route::prefix("admin")->name('admin.')->middleware(['auth', 'can:admin'])->group(function () {
    
    Route::prefix('gallery/{gallery}')->name('gallery_detail.')->group(function () {
        Route::get('/preview', [GalleryController::class, 'preview'])->name('preview');
        Route::resource('gallery_items', GalleryItemsController::class);
        Route::prefix('gallery_items/{gallery_item}')->name('gallery_item.')->group(function () {
            Route::resource('media', MediaController::class);
        });
    });

    Route::controller(HomeController::class)->group(function () {
        Route::get("/", "index")->name("admin_home");
        Route::get("/dashboard", "index")->name("admin_dashboard");
    });

    
    Route::controller(TagsController::class)->prefix('tags/{tag}')->name('tags.')->group(function () {
        Route::post('/attach', 'attach')->name('attach');
        Route::delete('/attach', 'detach')->name('delete_attach');
        Route::delete('/detach', 'detach')->name('detach');
        Route::post('/detach', 'detach')->name('post_detach');
    });


    Route::prefix('/services/{service}')->name('service_detail.')->group(function () {
        Route::patch('/service_items/sort', [ServiceItemsController::class, 'sort'])->name('sort_items');
        Route::resource('service_items', ServiceItemsController::class);
        Route::patch("/tiers/{tier}/position", [ServiceTiersController::class, "updatePosition"])->name("update_tier_position");
        Route::resource("tiers", ServiceTiersController::class);
    });

    Route::get("/messages/{message}/content", [CommunicationsController::class, "showContent"])->name("message_content");
    
    
    
    Route::resources([
        'tags' => TagsController::class,
        'gallery' => GalleryController::class,
        'services' => ServicesController::class,
        'media' => MediaController::class,
        "bookings" => BookingsController::class,
        "messages" => CommunicationsController::class,
        "customers" => CustomersController::class,
        "payments" => PaymentsController::class,
        "customer_events" => CustomerEventsController::class,
        "menu_items" => MenuItemsController::class,
    ]);

    Route::prefix("/customer_events/{customer_event}")->name('customer_event_detail.')->group(function () {
        Route::post("/guests/import", [CustomerEventGuestsAdminController::class, "handleImport"])->name('guests.import');
        Route::get('/guests/print', [CustomerEventGuestsAdminController::class, 'getPrintView'])->name('guests.print');
        Route::post("/guests/{guest}/barcode", [CustomerEventGuestsAdminController::class, "updateBarcode"]);
        Route::patch("/guests/{guest}/barcode", [CustomerEventGuestsAdminController::class, "updateBarcode"]);
        Route::resource('guests', CustomerEventGuestsAdminController::class);
    });

    

   
    

    Route::prefix('/posts/{post}')->name('post_detail.')->group(function () {
        Route::resource('post_items', PostItemsController::class);
    });

    

    Route::prefix('/events/{post}')->name('event_detail.')->group(function () {
        Route::resource('event_items', PostItemsController::class);
    });

    Route::resource('posts', PostsController::class);
    
    
});

Route::prefix('system')
->middleware(['can:system'])
->name('system.')
->group(function () {
    Route::controller(ToolsController::class)->group(function() {
        Route::post('/c', 'executeCache')->name('cache');
    });
});
