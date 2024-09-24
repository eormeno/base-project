<?php

use App\Models\User;
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
