<?php

namespace App\Services\GuessTheNumber;

use App\FSM\StateStorageInterface;
use App\Models\GuessTheNumberGame;
use App\Repositories\GuessTheNumber\GameRepository;
use App\States\GuessTheNumber\Initial;

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
        $game->times_played++;
        $game->remaining_attempts = $this->gameConfigService->getMaxAttempts();
        $game->random_number = $random_number;
        $game->save();
    }

    public function calculateScore(): int
    {
        $game = $this->getGame();
        if ($game->remaining_attempts == 0) {
            return 0;
        }
        $remaining_attempts = $game->max_attempts - $game->remaining_attempts;
        return $remaining_attempts * 100;
    }

    public function totalScore(): int
    {
        $game = $this->getGame();
        return $game->score + $this->calculateScore();
    }

    public function endGame()
    {
        $game = $this->getGame();
        $game->finished = true;
        $game->score = $this->calculateScore() + $game->score;
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

    public function getInitialStateClass(): string
    {
        return Initial::class;
    }

    public function readState(): string|null
    {
        return $this->getGame()->state;
    }

    public function saveState(string $state): void
    {
        $game = $this->getGame();
        $game->state = $state;
        $game->save();
    }
}
