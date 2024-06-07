<?php

namespace App\Services\GuessTheNumber;

use App\Models\GuessTheNumberGame;

class GameService extends AbstractGuessService
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
        $this->checkNumberIsCheat($number, function () {
            $this->gameRepository->updateRemainingAttempts(1);
        });
        $this->checkNumberOutOfRange($number);
        $this->checkNumberIsGuessed($number, function () {
            $this->endGame();
        });
        $this->checkNumberIsLowerThanRandomNumber($number, function () {
            $this->gameRepository->decreaseRemainingAttempts();
        }, function () {
            $this->endGame();
        });
        $this->checkNumberIsGreaterThanRandomNumber($number, function () {
            $this->gameRepository->decreaseRemainingAttempts();
        }, function () {
            $this->endGame();
        });
    }

    public function getRandomNumber(): int
    {
        $game = $this->getGame();
        return $game->random_number;
    }

    private function calculateRandomNumber(): int
    {
        return $this->clueService->findRandomNumber();
    }
}
