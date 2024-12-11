<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GameAppController;
use App\Http\Controllers\GuessTheNumberController;
use App\Http\Controllers\MythicTreasureQuestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/marketing', function () {
    return view('marketing.banner');
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {

    Route::get('/dashboard', function () {
        $gameApps = \App\Models\GameApp::all();
        return view('dashboard', compact('gameApps'));
    })->name('dashboard');

    Route::prefix('guess-the-number')->group(function () {
        Route::get('/', [GuessTheNumberController::class, 'index'])->name('guess-the-number');
        Route::post('/', [GuessTheNumberController::class, 'event'])->name('guess-the-number');
        Route::get('/reset', [GuessTheNumberController::class, '_reset'])->name("guess-the-number.reset");
    });

    Route::prefix('mythic-treasure-quest')->group(function () {
        Route::get('/', [MythicTreasureQuestController::class, 'index'])->name('mythic-treasure-quest');
        Route::post('/', [MythicTreasureQuestController::class, 'event'])->name('mythic-treasure-quest');
        Route::get('/reset', [MythicTreasureQuestController::class, '_reset'])->name("mythic-treasure-quest.reset");
    });

    Route::get('/poll-events', [EventController::class, 'pollEvents'])->name('poll-events');
    Route::get('/event-test', [EventController::class, 'triggerEvent'])->name('trigger-event-test');

    Route::get('/game-app/{gameApp}/play', [GameAppController::class, 'play'])->name('play');
    Route::post('/game-app/{game}', [GameAppController::class, 'event'])->name('event');
});
