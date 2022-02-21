<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GalleryItemsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\ServicesController;


Route::prefix("admin")->name('admin.')->middleware(['can:admin'])->group(function () {
    Route::resources([
        'gallery' => GalleryController::class,
        'posts' => PostsController::class,
        'events' => PostsController::class,
        'services' => ServicesController::class,
        'media' => MediaController::class,
    ]);

    Route::prefix('gallery/{gallery}')->name('gallery_detail.')->group(function () {
        Route::resource('gallery_items', GalleryItemsController::class);
        Route::prefix('gallery_items/{gallery_item}')->name('gallery_item.')->group(function () {
            Route::resource('media', MediaController::class);
        });
    });
});