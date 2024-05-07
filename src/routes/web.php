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

    Route::get('/play/{game_id?}', function () {
        return view('play.board', ['game_id' => request()->route('game_id')]);
    })->name('play');

});
