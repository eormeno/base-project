<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\GuessTheNumber\GuessTheNumberGameServiceManager;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GuessTheNumberGame>
 */
class GuessTheNumberGameFactory extends Factory
{

    public function definition(): array
    {
        $configService = resolve(GuessTheNumberGameServiceManager::class)->gameConfigService;
        $min_number = $configService->getMinNumber();
        $max_number = $configService->getMaxNumber();
        $max_attempts = $configService->getMaxAttempts();
        $half_attempts = $configService->getHalfAttempts();
        return [
            'min_number' => $min_number,
            'max_number' => $max_number,
            'max_attempts' => $max_attempts,
            'half_attempts' => $half_attempts,
            'remaining_attempts' => $max_attempts
        ];
    }

    public function fakeGame(): static
    {
        return $this->state(
            function (array $attributes) {
                $configService = resolve(GuessTheNumberGameServiceManager::class)->gameConfigService;
                $min_number = $configService->getMinNumber();
                $max_number = $configService->getMaxNumber();
                $max_attempts = $configService->getMaxAttempts();
                $fake_random_number = random_int($min_number, $max_number);
                $fake_attempts = random_int(1, $max_attempts);
                $fake_score = random_int(1, 20) * 100;
                return [
                    'remaining_attempts' => $fake_attempts,
                    'random_number' => $fake_random_number,
                    'score' => $fake_score,
                ];
            }
        )->sequence(
                ['state' => 'initial'],
                ['state' => 'playing'],
                ['state' => 'success'],
                ['state' => 'game_over']
            );
    }
}
