<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

Artisan::command('fresh', function () {
    $this->call('migrate:refresh', ['--seed' => true]);
})->describe('Fresh database');

Artisan::command('users', function () {
    $users = User::all(['id', 'name', 'email'])->toArray();
    $this->table(['id', 'name', 'email'], $users);
})->purpose('Display users');
