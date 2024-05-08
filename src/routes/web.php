<?php

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

    Route::get('/guess-the-number', function () {
        return view('play.guess-the-number');
    })->name('guess-the-number');

    Route::get('/clash-of-triad/{game_id?}', function () {
        return view('play.clash-of-triad', ['game_id' => request()->route('game_id')]);
    })->name('clash-of-triad');

});
