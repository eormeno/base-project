<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GuessTheNumber\GuessTheNumberController;

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

    Route::get(
        'guess-the-number',
        [GuessTheNumberController::class, 'index']
    )->name('guess-the-number');
    Route::post(
        'guess-the-number',
        [GuessTheNumberController::class, 'event']
    )->name('guess-the-number');
    Route::get(
        'guess-the-number/reset',
        [GuessTheNumberController::class, 'reset']
    )->name('guess-the-number.reset');

    Route::get('/clash-of-triad/{game_id?}', function () {
        return view('play.clash-of-triad', ['game_id' => request()->route('game_id')]);
    })->name('clash-of-triad');

    Route::get('/poll-events', [EventController::class, 'pollEvents'])->name('poll-events');
    Route::get('/event-test', [EventController::class, 'triggerEvent'])->name('trigger-event-test');

});
