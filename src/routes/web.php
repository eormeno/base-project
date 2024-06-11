<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GuessTheNumberController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('guess-the-number')->group(function () {
        Route::get('/', [GuessTheNumberController::class, 'index'])->name('guess-the-number');
        Route::post('/', [GuessTheNumberController::class, 'event'])->name('guess-the-number');
        Route::get('/reset', [GuessTheNumberController::class, 'reset'])->name('guess-the-number.reset');
    });

    Route::get('/poll-events', [EventController::class, 'pollEvents'])->name('poll-events');
    Route::get('/event-test', [EventController::class, 'triggerEvent'])->name('trigger-event-test');

});
