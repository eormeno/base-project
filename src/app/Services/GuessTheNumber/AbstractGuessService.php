<?php

namespace App\Services\GuessTheNumber;

use App\Services\AbstractServiceComponent;
use App\Exceptions\GuessTheNumber\FailException;
use App\Exceptions\GuessTheNumber\InfoException;
use App\Exceptions\GuessTheNumber\SuccessException;
use App\Exceptions\GuessTheNumber\GameOverException;
use App\Exceptions\GuessTheNumber\NotInRangeException;

abstract class AbstractGuessService extends AbstractServiceComponent
{

    protected function checkNumberIsCheat($number, callable $callback = null)
    {
        if ($number == $this->gameConfigService->getCheatNumber()) {
            if ($callback) {
                $callback();
            }
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

    protected function checkNoEnoughAttempts(callable $callback = null)
    {
        $game = $this->gameRepository->getGame();
        if ($game->remaining_attempts == 0) {
            if ($callback) {
                $callback();
            }
            throw new GameOverException();
        }
    }

    protected function checkNumberIsLowerThanRandomNumber($number, callable $callback = null, callable $noEnoughAttemptsCallback = null)
    {
        $game = $this->gameRepository->getGame();

        if ($number < $game->random_number) {
            if ($callback) {
                $callback();
            }
            $this->checkNoEnoughAttempts($noEnoughAttemptsCallback);
            throw new FailException($this->messageService->greaterMessage($number));
        }
    }

    protected function checkNumberIsGreaterThanRandomNumber($number, callable $callback = null, callable $noEnoughAttemptsCallback = null)
    {
        $game = $this->gameRepository->getGame();
        if ($number > $game->random_number) {
            if ($callback) {
                $callback();
            }
            $this->checkNoEnoughAttempts($noEnoughAttemptsCallback);
            throw new FailException($this->messageService->lowerMessage($number));
        }
    }

    protected function checkNumberIsGuessed($number, callable $callback = null)
    {
        $game = $this->gameRepository->getGame();
        if ($number == $game->random_number) {
            if ($callback) {
                $callback();
            }
            throw new SuccessException();
        }
    }
}
