<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\TokensController;
use App\Http\Controllers\Api\Admin\CustomerEventsController;
use App\Http\Controllers\Api\MenuItemsController;
use App\Http\Controllers\Api\Admin\MenuItemsController as AdminMenuItemsController;
use App\Http\Controllers\Api\Admin\ServicesController;
use App\Http\Controllers\Api\Admin\UsersController;
use App\Http\Controllers\Api\Admin\CustomerBagController;
use App\Http\Controllers\Api\Admin\CustomerMenuItemsController;
use App\Http\Controllers\Api\Admin\EventLocationsController;
use App\Http\Controllers\Api\Admin\LocationTablesController;
use App\Http\Controllers\Api\Admin\SplurgeEventUsersController;

use App\Http\Controllers\Api\Admin\SplurgeEventUserTablesController;


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

Route::post('/tokens', [TokensController::class, 'create'])->middleware('login_once');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resources([
        'menu_items' => MenuItemsController::class
    ]);

    
});

Route::middleware(['auth:sanctum'])->prefix('admin')->name('api.admin.')->group(function () {
   
    Route::resource('users', UsersController::class)->only(['index', 'show', 'update', 'store', 'destroy']);

    Route::resource('services', ServicesController::class)->only(['index']);

    Route::get('/customer_events/stats', [CustomerEventsController::class, 'getStats'])->name('customer_events.stats');

    Route::get('/customer_events/{customer_event}/stats', [CustomerEventsController::class, 'getSingleStats'])->name('customer_events.detail_stats');
    
    

    

    Route::resource('customer_events', CustomerEventsController::class)->only('index', 'show', 'store', 'update', 'destroy');

    Route::post("/menu_items/all", [AdminMenuItemsController::class, 'storeAll'])->name('storeManyMenuItems');
    
    Route::resource('menu_items', AdminMenuItemsController::class);

    Route::get('/guests/lookup', [SplurgeEventUsersController::class, 'lookup'])->name('lookupAnyGuest');

    Route::prefix('customer_events/{event}')->name('customer_event_details.')->group(function () {

        Route::get("/guests/lookup", [SplurgeEventUsersController::class, 'lookup'])->name('lookupGuest');

        Route::resource('guests', SplurgeEventUsersController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

        Route::resource('locations', EventLocationsController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

        Route::prefix('/guests/{guest}')->name('event_guest_details.')->group(function () {
            Route::resource('bag', CustomerBagController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
            Route::resource('menu_items', CustomerMenuItemsController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        });

        Route::prefix('/locations/{location}')->name('event_location_details.')->group(function () {
            Route::resource('tables', LocationTablesController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
            Route::delete('/assignments/all', [SplurgeEventUserTablesController::class, 'unassignAll'])->name('assignments.removeAll');
            Route::post('/assignments/all', [SplurgeEventUserTablesController::class, 'assignAll'])->name('assignments.addAll');
            Route::resource('assignments', SplurgeEventUserTablesController::class)->only(['index', 'store', 'show', 'destroy']);
        });

        // Route::post('/guests/import', [CustomerEventGuestsController::class, 'handleImport'])->name('guests.import');

        // Route::resource('tables', EventTablesController::class)->only(['index', 'store', 'update', 'destroy']);
        
        // Route::resource('guests', CustomerEventGuestsController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

        // Route::get('/guests/lookup', [CustomerEventGuestsController::class, 'lookup']);
        
        // Route::post("/guests/{guest}/barcode", [CustomerEventGuestsController::class, "updateBarcode"])->name('set_barcode');
        
        // Route::patch("/guests/{guest}/barcode", [CustomerEventGuestsController::class, "updateBarcode"])->name('update_barcode');

        // Route::prefix('guests/{guest}')->name('guest_details.')->group(function () {
        //     Route::resource('menu_preferences', GuestMenuPreferencesController::class);
        // });
    });
});