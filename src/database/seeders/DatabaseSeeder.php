<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\GuessTheNumberGame;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ])->create();
    }
}
