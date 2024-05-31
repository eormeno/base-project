<?php

namespace App\Http\Controllers\GuessTheNumber\Services;
use App\Http\Controllers\GuessTheNumber\Repositories\GuessTheNumberGameRepository;

class GameService
{
    public function __construct(
        protected GuessService $guessService,
        protected GameConfigService $gameConfigService,
        protected GuessTheNumberGameRepository $gameRepository
    ) {
    }

    public function startGame()
    {
        $random_number = $this->calculateRandomNumber();
        $this->gameRepository->createNewGame($random_number);
    }

    public function guess($number)
    {
        $this->guessService->guess($number);
    }

    public function getRemainingAttempts(): int
    {
        return $this->gameRepository->getRemainingAttempts();
    }

    private function calculateRandomNumber(): int
    {
        $min = $this->gameConfigService->getMinNumber();
        $max = $this->gameConfigService->getMaxNumber();
        return rand($min, $max);
    }
}
