<?php

namespace App\Repositories\GuessTheNumber;

use App\Models\GuessTheNumberGame;
use App\Services\GuessTheNumber\GameConfigService;

class GameRepository
{
    public function __construct(
        protected GameConfigService $gameConfigService
    ) {
    }

    public function createNewGame() : void
    {
        $game = new GuessTheNumberGame();
        $game->user_id = auth()->id();
        $game->state = 'initial';
        $game->min_number = $this->gameConfigService->getMinNumber();
        $game->max_number = $this->gameConfigService->getMaxNumber();
        $game->max_attempts = $this->gameConfigService->getMaxAttempts();
        $game->half_attempts = $this->gameConfigService->getHalfAttempts();
        $game->remaining_attempts = $this->gameConfigService->getMaxAttempts();
        $game->random_number = 0;
        $game->score = 0;
        $game->finished = false;
        $game->save();
    }

    private function existsGame(): bool
    {
        return auth()->user()->guessTheNumberGame()->exists();
    }

    public function getGame(): GuessTheNumberGame
    {
        if (!$this->existsGame()) {
            $this->createNewGame();
        }
        return auth()->user()->guessTheNumberGame;
    }
}
