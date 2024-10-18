<?php

use App\Models\User;
use App\Models\GuessTheNumberGame;
use Illuminate\Support\Facades\Artisan;

Artisan::command('fresh', function () {
    $this->call('migrate:refresh', ['--seed' => true]);
})->describe('Fresh database');

Artisan::command('user:list', function () {
    $users = User::all()->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    });
    $this->table(['id', 'name', 'email'], $users);
})->purpose('Display users');

// List all the guess_the_number_games
Artisan::command('guess_the_number_game:list', function () {
    $games = GuessTheNumberGame::all()->map(function ($game) {
        return [
            'id' => $game->id,
            'user_id' => $game->user_id,
            'remaining_attempts' => $game->remaining_attempts,
            'random_number' => $game->random_number,
            'score' => $game->score,
        ];
    });
    $this->table([
        'id',
        'user_id',
        'remaining_attempts',
        'random_number',
        'score',
    ], $games);
})->purpose('Display guess_the_number_games');
