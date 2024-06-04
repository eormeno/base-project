<?php

namespace Database\Seeders;

use App\Models\GuessTheNumberGame;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->createIfNotExists([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        GuessTheNumberGame::factory()
            ->for(User::first())
            ->create();

        User::factory()
            ->has(GuessTheNumberGame::factory()->count(1)) // Cada usuario tendrá 1 juego
            ->count(10) // Crear 10 usuarios
            ->create();
    }
}
