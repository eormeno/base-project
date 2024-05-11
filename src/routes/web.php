<?php

use App\Http\Controllers\GuessTheNumberController;
use App\Livewire\Contador;
use Illuminate\Support\Facades\Route;

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

    Route::get('guess-the-number', [GuessTheNumberController::class, 'index'])->name('guess-the-number');
    Route::post('guess-the-number', [GuessTheNumberController::class, 'guess'])->name('guess-the-number.guess');
    Route::get('guess-the-number/reset', [GuessTheNumberController::class, 'reset'])->name('guess-the-number.reset');
    Route::get('guess-the-number/play-again', [GuessTheNumberController::class, 'playAgain'])->name('guess-the-number.play-again');

    Route::get('/clash-of-triad/{game_id?}', function () {
        return view('play.clash-of-triad', ['game_id' => request()->route('game_id')]);
    })->name('clash-of-triad');

});
