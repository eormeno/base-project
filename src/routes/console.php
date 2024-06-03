<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

Artisan::command('fresh', function () {
    if (config('database.default') === 'sqlite') {
        if (file_exists(database_path('database.sqlite'))) {
            if (is_writable(database_path('database.sqlite'))) {
                unlink(database_path('database.sqlite'));
            } else {
                $this->error('File database.sqlite is not writable');
                return;
            }
        }
        $this->call('migrate', ['--force' => true]);
        $this->call('db:seed');
    }
})->describe('Fresh database');

Artisan::command('users', function () {
    $users = User::all(['id', 'name', 'email'])->toArray();
    $this->table(['id', 'name', 'email'], $users);
})->purpose('Display users');
