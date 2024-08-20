<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GuessTheNumberController;
use App\Http\Controllers\MythicTreasureQuestController;

Route::get('/', function () {
    return view('welcome');
});

// una ruta que recibe como parametro el nombre de usuario y una clave, si es correcta retorna el un json con mensaje "Bienvenido" de lo contrario retorna "Usuario o clave incorrecta"
Route::get('/entrar/{username}/{password}', function ($username, $password) {
    if ($username == 'admin' && $password == '1234') {
        return response()->json(['message' => 'Bienvenido']);
    } else {
        return response()->json(['message' => 'Usuario o clave incorrecta']);
    }
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    buildRoutes('guess-the-number', GuessTheNumberController::class);
    buildRoutes('mythic-treasure-quest', MythicTreasureQuestController::class);

    Route::get('/poll-events', [EventController::class, 'pollEvents'])->name('poll-events');
    Route::get('/event-test', [EventController::class, 'triggerEvent'])->name('trigger-event-test');

});

function buildRoutes(string $routeName, $controller)
{
    Route::prefix($routeName)->group(function () use ($routeName, $controller) {
        Route::get('/', [$controller, 'index'])->name($routeName);
        Route::post('/', [$controller, 'event'])->name($routeName);
        Route::get('/reset', [$controller, '_reset'])->name("$routeName.reset");
    });
}
