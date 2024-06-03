<?php

namespace App\Services\GuessTheNumber;

use App\Models\GuessTheNumberGame;
use App\Services\AbstractServiceComponent;

class GameService extends AbstractServiceComponent
{
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
        $remaining_attempts = $game->remaining_attempts;
        return $remaining_attempts * 100;
    }

    public function totalScore(): int
    {
        $game = $this->getGame();
        return $game->score;
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
}
