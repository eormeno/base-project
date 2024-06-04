<?php

namespace Database\Factories;

use App\Models\User;
use App\Services\GuessTheNumber\GameConfigService;
use App\Services\GuessTheNumber\ServiceManager;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuessTheNumberGameFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $gameConfigService = resolve(ServiceManager::class)->gameConfigService;

        return [
            'user_id' => User::factory(),
            'min_number' => $gameConfigService->gameConfigService->getMinNumber(),
            'max_number' => $gameConfigService->gameConfigService->getMaxNumber(),
            'max_attempts' => $gameConfigService->gameConfigService->getMaxAttempts(),
            'half_attempts' => $gameConfigService->gameConfigService->getHalfAttempts(),
            'remaining_attempts' => $gameConfigService->gameConfigService->getMaxAttempts(),
            'finished' => true,
            'score' => rand(1, 100) * 100,
            'times_played' => rand(1, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forUser(User $user)
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

}
