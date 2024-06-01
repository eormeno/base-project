<?php

namespace App\Services\GuessTheNumber;

use App\FSM\StateStorageInterface;
use App\Models\GuessTheNumberGame;
use App\Repositories\GuessTheNumber\GameRepository;

class GameService implements StateStorageInterface
{
    public function __construct(
        protected GuessService $guessService,
        protected GameConfigService $gameConfigService,
        protected GameRepository $gameRepository
    ) {
    }

    public function getGame(): GuessTheNumberGame
    {
        return $this->gameRepository->getGame();
    }

    public function startGame()
    {
        $game = $this->getGame();
        $random_number = $this->calculateRandomNumber();
        $game->remaining_attempts = $this->gameConfigService->getMaxAttempts();
        $game->random_number = $random_number;
        $game->save();
    }

    public function guess($number)
    {
        $this->guessService->guess($number);
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
        return $this->getGame()->state;
    }

    public function setState(string $state): void
    {
        $game = $this->getGame();
        $game->state = $state;
        $game->save();
    }
}
