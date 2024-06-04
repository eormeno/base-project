<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\GuessTheNumberGame;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\GuessTheNumber\GuessTheNumberGameServiceManager;

class GuessTheNumberGameFactory extends Factory
{
    protected $model = GuessTheNumberGame::class;
    protected int $minNumber;
    protected int $maxNumber;
    protected int $maxAttempts;
    protected int $halfAttempts;

    public function __construct()
    {
        parent::__construct();
        $gameConfigService = resolve(GuessTheNumberGameServiceManager::class)->gameConfigService;
        $this->minNumber = $gameConfigService->getMinNumber();
        $this->maxNumber = $gameConfigService->getMaxNumber();
        $this->maxAttempts = $gameConfigService->getMaxAttempts();
        $this->halfAttempts = $gameConfigService->getHalfAttempts();
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'min_number' => $this->minNumber,
            'max_number' => $this->maxNumber,
            'max_attempts' => $this->maxAttempts,
            'half_attempts' => $this->halfAttempts,
            'remaining_attempts' => $this->maxAttempts,
        ];
    }

    public function configure() : Factory
    {
        return $this->afterMaking(function (GuessTheNumberGame $game) {
            $game->number_to_guess = rand($game->min_number, $game->max_number);
        });
    }

    public function withFakeData() : Factory
    {
        $randonRemainingAttempts = rand(1, $this->maxAttempts);
        return $this->state(fn(array $attributes) => [
            'finished' => true,
            'remaining_attempts' => $randonRemainingAttempts,
            'score' => rand(1, 100) * 100,
            'times_played' => rand(1, 100),
        ]);
    }

}
