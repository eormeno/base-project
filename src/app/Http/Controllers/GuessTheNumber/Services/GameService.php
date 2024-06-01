<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

use App\FSM\StateStorageInterface;
use App\Http\Controllers\GuessTheNumber\Repositories\GuessTheNumberGameRepository;

class GameService implements StateStorageInterface
{
    public function __construct(
        protected GuessService $guessService,
        protected GameConfigService $gameConfigService,
        protected GuessTheNumberGameRepository $gameRepository
    ) {
    }

    public function getGame(): array
    {
        if (!$this->gameRepository->existsGame()) {
            $this->gameRepository->createNewGame();
        }
        return $this->gameRepository->getGame();
    }

    public function startGame()
    {
        $random_number = $this->calculateRandomNumber();
        $this->gameRepository->setRandomNumber($random_number);
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

    public function getInitialStateDashedName(): string
    {
        return 'initial';
    }

    public function getState(): string
    {
        return $this->gameRepository->getGameState();
    }

    public function setState(string $state): void
    {
        $this->gameRepository->setGameState($state);
    }
}
