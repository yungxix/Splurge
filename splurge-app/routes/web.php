<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ServicesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', HomeController::class);

Route::prefix('services')->name('services.')->controller(ServicesController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{service}', 'show')->name('show');
});

Route::prefix('events')->controller(EventsController::class)->name('events.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{post}', 'show')->where(['post' => '[0-9]+'])->name('show');
    Route::get('/{event_type}', 'ofType')->name('events_of_type');
});



Route::prefix('gallery')->controller(GalleryController::class)->group(function () {
    Route::get('/', 'index');
});




Route::prefix('about')->controller(AboutController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/contact', 'showContact');
});
