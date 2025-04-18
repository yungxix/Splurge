<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\My\PaymentsController;
use App\Http\Controllers\My\MessagesController;


Route::prefix("my")->name("my.")->middleware([
    'auth:my',
    'saccess'
])->group(function () {
    
    Route::prefix("/bookings/{booking}")->name('booking_details.')->group(function () {

        Route::get('/payments/accept', [PaymentsController::class, "acceptPayment"])->name('payments.accept');

        Route::get('/messages/{message}/content', [MessagesController::class, "showContent"])->name('messages.content');
        
        Route::resources([
            'payments' => PaymentsController::class,
            'messages' => MessagesController::class
        ]);
    });
});