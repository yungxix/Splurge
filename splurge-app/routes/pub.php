<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Pub\ServicesController;



Route::prefix('pub')->name('api.pub.')->group(function () {
    Route::resource('services', ServicesController::class, ['only' => 'index']);
});