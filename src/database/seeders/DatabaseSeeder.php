<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\GuessTheNumberGame;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        fake()->seed(10);
        User::factory()->adminUser()->has(GuessTheNumberGame::factory())->create();
        User::factory()->has(GuessTheNumberGame::factory()->fakeGame())->count(9)->create();
    }
}
