<?php

namespace App\Services\GuessTheNumber;

use App\Exceptions\GuessTheNumber\FailException;
use App\Exceptions\GuessTheNumber\InfoException;
use App\Exceptions\GuessTheNumber\SuccessException;
use App\Repositories\GuessTheNumber\GameRepository;
use App\Exceptions\GuessTheNumber\GameOverException;
use App\Exceptions\GuessTheNumber\NotInRangeException;

class GuessService
{
    public function __construct(
        protected GameRepository $gameRepository,
        protected GameConfigService $gameConfigService,
        protected GuessTheNumberMessageService $messageService
    ) {
    }

    public function guess($number)
    {
        $this->checkNumberIsCheat($number);
        $this->checkNumberIsNotBetween($number);
        $this->checkNumberIsGuessed($number);
        $this->checkNoEnoughAttempts();
        $this->checkNumberIsLowerThanRandomNumber($number);
        $this->checkNumberIsGreaterThanRandomNumber($number);
    }

    private function checkNumberIsCheat($number)
    {
        if ($number == $this->gameConfigService->getCheatNumber()) {
            $this->gameRepository->setRemainingAttempts(1);
            throw new InfoException($this->messageService->cheatMessage());
        }
    }

    private function checkNumberIsNotBetween($number)
    {
        $min = $this->gameConfigService->getMinNumber();
        $max = $this->gameConfigService->getMaxNumber();
        if ($number < $min || $number > $max) {
            $message = $this->messageService->invalidNumberMessage();
            throw new NotInRangeException($message, $number, $min, $max);
        }
    }

    private function checkNoEnoughAttempts()
    {
        if ($this->gameRepository->getRemainingAttempts() <= 1) {
            $this->gameRepository->setGameOver();
            throw new GameOverException();
        }
    }

    private function checkNumberIsLowerThanRandomNumber($number)
    {
        if ($number < $this->gameRepository->getRandomNumber()) {
            $this->gameRepository->decreaseRemainingAttempts();
            throw new FailException($this->messageService->greaterMessage($number));
        }
    }

    private function checkNumberIsGreaterThanRandomNumber($number)
    {
        if ($number > $this->gameRepository->getRandomNumber()) {
            $this->gameRepository->decreaseRemainingAttempts();
            throw new FailException($this->messageService->lowerMessage($number));
        }
    }

    private function checkNumberIsGuessed($number)
    {
        if ($number == $this->gameRepository->getRandomNumber()) {
            $this->gameRepository->setGameSuccess();
            throw new SuccessException();
        }
    }
}
