<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ServicesController;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\AccessController;

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


require __DIR__.'/auth.php';
require __DIR__ . '/admin.php';
require __DIR__  . '/my.php';


Route::controller(CalendarsController::class)->group(function () {
    
});

Route::controller(AccessController::class)->prefix('access')->name('access.')->group(function () {
    Route::get('/', 'index');
    Route::get("/{entity}/{uuid}", "create")->name('create');
    Route::post("/logout", "destroy")->name('destroy');
});


Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    
    Route::get('', 'index')->name('home2');
    
    Route::get('/dashboard', 'showDashboard')->middleware(['auth'])->name('dashboard');
    
    Route::get('/search', 'getSearch')->name('search');

    Route::get('/search/tagged', 'getTaggedSearch')->name('tagged');

    Route::get("/redir", "redirectForRole")->middleware(["auth"]);

    Route::get("/rdir", "redirectForRole")->middleware(["auth"]);
});


Route::resource("payments", PaymentsController::class);


Route::controller(GalleryController::class)->prefix('gallery')->group(function () {
    
    Route::get('/{gallery}', 'show')->name('gallery.show');

    Route::get('/', 'index')->name('gallery.index');
});


Route::controller(ServicesController::class)->prefix('services')->group(function () {
    
    Route::get('/{service}', 'show')->name('services.show');
    Route::get('/', 'index')->name('services.index');
});

Route::controller(EventsController::class)->prefix('events')->name('events.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::get('{post}', 'show')->where(['post' => '[0-9]+'])->name('show');
    Route::get('{event_type}', 'ofType')->name('events_of_type');
});

Route::controller(EventsController::class)->prefix('posts')->name('posts.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::get('{post}', 'show')->where(['post' => '[0-9]+'])->name('show');
    Route::get('{event_type}', 'ofType')->name('events_of_type');
});



Route::prefix('/about')->controller(AboutController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/contact', 'showContact');
});


