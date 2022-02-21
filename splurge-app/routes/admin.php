<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\ServicesController;


Route::prefix("admin")->name('admin.')->middleware(['can:admin'])->group(function () {
    Route::resources([
        'gallery' => GalleryController::class,
        'posts' => PostsController::class,
        'events' => PostsController::class,
        'services' => ServicesController::class,
    ]);
});