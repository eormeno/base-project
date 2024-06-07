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
}
