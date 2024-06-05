<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\GuessTheNumberGame;
use App\Services\GuessTheNumber\GuessTheNumberGameServiceManager;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        fake()->seed(10);

        $configService = resolve(GuessTheNumberGameServiceManager::class)->gameConfigService;
        $min_number = $configService->getMinNumber();
        $max_number = $configService->getMaxNumber();
        $max_attempts = $configService->getMaxAttempts();
        $half_attempts = $configService->getHalfAttempts();

        User::factory(
            [
                'name' => 'Pepe',
                'email' => 'pepe@example.com',
                'password' => bcrypt('111')
            ]
        )->has(GuessTheNumberGame::factory([
            'state' => 'initial',
            'min_number' => $min_number,
            'max_number' => $max_number,
            'max_attempts' => $max_attempts,
            'half_attempts' => $half_attempts,
            'remaining_attempts' => $max_attempts
        ]))->create();

        User::factory()->has(GuessTheNumberGame::factory()->state(
            function (array $attributes) use ($min_number, $max_number, $max_attempts, $half_attempts) {
                $fake_random_number = random_int($min_number, $max_number);
                $fake_attempts = random_int(1, $max_attempts);
                return [
                    'min_number' => $min_number,
                    'max_number' => $max_number,
                    'max_attempts' => $max_attempts,
                    'half_attempts' => $half_attempts,
                    'remaining_attempts' => $fake_attempts,
                    'random_number' => $fake_random_number,
                ];
            }
        )->sequence(
            ['state' => 'initial'],
            ['state' => 'playing'],
            ['state' => 'success'],
            ['state' => 'game_over']
        ))->count(9)->create();
    }
}
