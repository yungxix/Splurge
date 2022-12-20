<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\TokensController;
use App\Http\Controllers\Api\Admin\CustomerEventGuestsController;
use App\Http\Controllers\Api\Admin\CustomerEventsController;
use App\Http\Controllers\Api\MenuItemsController;
use App\Http\Controllers\Api\Admin\MenuItemsController as AdminMenuItemsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['once'])->group(function () {
    Route::post('/tokens', [TokensController::class, 'create']);
});

Route::middleware(['auth:sanctum', 'scan:admin,sanctum'])->group(function () {
    Route::resources([
        'menu_items' => MenuItemsController::class
    ]);
});

Route::middleware(['auth:sanctum', 'scan:admin,sanctum'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('customer_events', CustomerEventsController::class)->only('index', 'show', 'store', 'update', 'destroy');
    Route::resource('menu_items', AdminMenuItemsController::class);

    Route::prefix('customer_events/{customer_event}')->name('customer_event_details.')->group(function () {
        Route::resource('guests', CustomerEventGuestsController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        Route::post("/guests/{guest}/barcode", [CustomerEventGuestsController::class, "updateBarcode"])->name('set_barcode');
        Route::patch("/guests/{guest}/barcode", [CustomerEventGuestsController::class, "updateBarcode"])->name('update_barcode');

        Route::prefix('guests/{guest}')->name('guest_details.')->group(function () {
            Route::post('menu_preference', [CustomerEventGuestsController::class, 'addMenuItem'])->name('add_menu_preference');
            Route::post('menu_preferences', [CustomerEventGuestsController::class, 'setMenuItems'])->name('replace_menu_preferences');;
            Route::delete('menu_preference', [CustomerEventGuestsController::class, 'removeMenuItem'])->name('remove_menu_preference');;
        });
    });
});