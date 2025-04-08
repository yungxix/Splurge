<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ToolsController;


Route::prefix('tools')
->middleware(['allowTooling'])
->name('tools.')
->group(function () {
    Route::controller(ToolsController::class)->group(function() {
        Route::post('/c', 'executeCache')->name('cache');
    });
});