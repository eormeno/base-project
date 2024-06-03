<?php

namespace App\Repositories\GuessTheNumber;

use App\Models\GuessTheNumberGame;
use App\Services\AbstractServiceComponent;

class GameRepository extends AbstractServiceComponent
{
    public function createEmptyNewGame() : void
    {
        $game = new GuessTheNumberGame();
        $game->user_id = auth()->id();
        $game->min_number = $this->gameConfigService->getMinNumber();
        $game->max_number = $this->gameConfigService->getMaxNumber();
        $game->max_attempts = $this->gameConfigService->getMaxAttempts();
        $game->half_attempts = $this->gameConfigService->getHalfAttempts();
        $game->remaining_attempts = $this->gameConfigService->getMaxAttempts();
        $game->save();
    }

    public function restartExistingGame($game) : void
    {
        $game->remaining_attempts = $this->gameConfigService->getMaxAttempts();
        $game->half_attempts = $this->gameConfigService->getHalfAttempts();
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
            $this->createEmptyNewGame();
        }
        return auth()->user()->guessTheNumberGame;
    }
}
