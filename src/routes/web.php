<?php

use Illuminate\Support\Facades\Route;
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
    Route::get(
        'guess-the-number/want-to-play',
        [GuessTheNumberController::class, 'wantToPlay']
    )->name('guess-the-number.want-to-play');
    Route::post(
        'guess-the-number',
        [GuessTheNumberController::class, 'guess']
    )->name('guess-the-number.guess');
    Route::get(
        'guess-the-number/reset',
        [GuessTheNumberController::class, 'reset']
    )->name('guess-the-number.reset');
    Route::get(
        'guess-the-number/play-again',
        [GuessTheNumberController::class, 'playAgain']
    )->name('guess-the-number.play-again');

    Route::get('/clash-of-triad/{game_id?}', function () {
        return view('play.clash-of-triad', ['game_id' => request()->route('game_id')]);
    })->name('clash-of-triad');

});
