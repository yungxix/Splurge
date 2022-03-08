<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GalleryItemsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\PostItemsController;

use App\Http\Controllers\Admin\ToolsController;

Route::prefix("admin")->name('admin.')->middleware(['auth', 'can:admin'])->group(function () {
    
    Route::prefix('gallery/{gallery}')->name('gallery_detail.')->group(function () {
        Route::resource('gallery_items', GalleryItemsController::class);
        Route::prefix('gallery_items/{gallery_item}')->name('gallery_item.')->group(function () {
            Route::resource('media', MediaController::class);
        });
    });

    
    Route::controller(TagsController::class)->prefix('tags/{tag}')->name('tags.')->group(function () {
        Route::post('/attach', 'attach')->name('attach');
        Route::delete('/attach', 'detach')->name('delete_attach');
        Route::delete('/detach', 'detach')->name('detach');
        Route::post('/detach', 'detach')->name('post_detach');
    });
    
    Route::resources([
        'tags' => TagsController::class,
        'gallery' => GalleryController::class,
        'posts' => PostsController::class,
        'events' => PostsController::class,
        'services' => ServicesController::class,
        'media' => MediaController::class,
    ]);

    Route::prefix('/posts/{post}')->name('post_detail.')->group(function () {
        Route::resource('post_items', PostItemsController::class);
    });
    
});

Route::prefix('system')
->middleware(['can:system'])
->name('system.')
->group(function () {
    Route::controller(ToolsController::class)->group(function() {
        Route::post('/c', 'executeCache')->name('cache');
    });
});
