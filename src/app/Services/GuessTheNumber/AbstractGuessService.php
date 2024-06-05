<?php

namespace App\Services\GuessTheNumber;

use App\Services\AbstractServiceComponent;
use App\Exceptions\GuessTheNumber\FailException;
use App\Exceptions\GuessTheNumber\InfoException;
use App\Exceptions\GuessTheNumber\SuccessException;
use App\Repositories\GuessTheNumber\GameRepository;
use App\Exceptions\GuessTheNumber\GameOverException;
use App\Exceptions\GuessTheNumber\NotInRangeException;

abstract class AbstractGuessService extends AbstractServiceComponent
{
    public function updateRemainingAttempts(int $remainingAttempts): void
    {
        $game = $this->gameRepository->getGame();
        $game->remaining_attempts = $remainingAttempts;
        $game->save();
    }

    public function decreaseRemainingAttempts(): void
    {
        $game = $this->gameRepository->getGame();
        $game->remaining_attempts--;
        $game->save();
    }

    protected function checkNumberIsCheat($number)
    {
        if ($number == $this->gameConfigService->getCheatNumber()) {
            $this->updateRemainingAttempts(1);
            throw new InfoException($this->messageService->cheatMessage());
        }
    }

    protected function checkNumberOutOfRange($number)
    {
        $min = $this->gameConfigService->getMinNumber();
        $max = $this->gameConfigService->getMaxNumber();
        if ($number < $min || $number > $max) {
            $message = $this->messageService->invalidNumberMessage();
            throw new NotInRangeException($message, $number, $min, $max);
        }
    }

    protected function checkNoEnoughAttempts()
    {
        $game = $this->gameRepository->getGame();
        if ($game->remaining_attempts == 0) {
            throw new GameOverException();
        }
    }

    protected function checkNumberIsLowerThanRandomNumber($number)
    {
        $game = $this->gameRepository->getGame();

        if ($number < $game->random_number) {
            $this->decreaseRemainingAttempts();
            $this->checkNoEnoughAttempts();
            throw new FailException($this->messageService->greaterMessage($number));
        }
    }

    protected function checkNumberIsGreaterThanRandomNumber($number)
    {
        $game = $this->gameRepository->getGame();
        if ($number > $game->random_number) {
            $this->decreaseRemainingAttempts();
            $this->checkNoEnoughAttempts();
            throw new FailException($this->messageService->lowerMessage($number));
        }
    }

    protected function checkNumberIsGuessed($number)
    {
        $game = $this->gameRepository->getGame();
        if ($number == $game->random_number) {
            throw new SuccessException();
        }
    }
}
