<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\TokensController;
use App\Http\Controllers\Api\Admin\CustomerEventGuestsController;
use App\Http\Controllers\Api\Admin\CustomerEventsController;
use App\Http\Controllers\Api\MenuItemsController;
use App\Http\Controllers\Api\Admin\MenuItemsController as AdminMenuItemsController;
use App\Http\Controllers\Api\Admin\ServicesController;
use App\Http\Controllers\Api\Admin\EventTablesController;
use App\Http\Controllers\Api\Admin\UsersController;
use App\Http\Controllers\Api\Admin\GuestMenuPreferencesController;
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

require __DIR__ . '/tools.php';

require __DIR__ . '/pub.php';

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user  = $request->user();
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'roles' => $user->roles->map(fn ($role) => $role->name)->all()
    ]);
});



Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    $user  = $request->user();
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'roles' => $user->roles->map(fn ($role) => $role->name)->all()
    ]);
});


Route::middleware(['once'])->group(function () {
    Route::post('/tokens', [TokensController::class, 'create']);
});

Route::middleware(['auth:sanctum', 'scan:admin,sanctum'])->group(function () {
    Route::resources([
        'menu_items' => MenuItemsController::class
    ]);

    
});

Route::middleware(['auth:sanctum', 'scan:admin,sanctum'])->prefix('admin')->name('api.admin.')->group(function () {
   
    Route::resource('users', UsersController::class)->only(['index', 'show', 'update', 'store', 'destroy']);

    Route::resource('services', ServicesController::class)->only(['index']);

    Route::get('/customer_events/{customer_event}/stats', [CustomerEventsController::class, 'getSingleStats'])->name('customer_events.detail_stats');
    
    Route::get('/customer_events/stats', [CustomerEventsController::class, 'getStats'])->name('customer_events.stats');

    

    Route::resource('customer_events', CustomerEventsController::class)->only('index', 'show', 'store', 'update', 'destroy');

    Route::resource('menu_items', AdminMenuItemsController::class);

    Route::get('/guests/lookup', [CustomerEventGuestsController::class, 'lookupAny']);

    Route::prefix('customer_events/{customer_event}')->name('customer_event_details.')->group(function () {

        Route::post('/guests/import', [CustomerEventGuestsController::class, 'handleImport'])->name('guests.import');

        Route::resource('tables', EventTablesController::class)->only(['index', 'store', 'update', 'destroy']);
        
        Route::resource('guests', CustomerEventGuestsController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

        Route::get('/guests/lookup', [CustomerEventGuestsController::class, 'lookup']);
        
        Route::post("/guests/{guest}/barcode", [CustomerEventGuestsController::class, "updateBarcode"])->name('set_barcode');
        
        Route::patch("/guests/{guest}/barcode", [CustomerEventGuestsController::class, "updateBarcode"])->name('update_barcode');

        Route::prefix('guests/{guest}')->name('guest_details.')->group(function () {
            Route::resource('menu_preferences', GuestMenuPreferencesController::class);
        });
    });
});