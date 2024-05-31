<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

use App\Http\Controllers\GuessTheNumber\Repositories\GuessTheNumberGameRepository;

class GuessService
{
    public function __construct(
        protected GuessTheNumberGameRepository $gameRepository,
        protected GameConfigService $gameConfigService
    ) {
    }

    public function guess($number)
    {
        $this->checkNumberIsCheat($number);
        $this->checkNumberIsNotBetween($number);
        $this->checkNoEnoughAttempts();
        $this->checkNumberIsLowerThanRandomNumber($number);
        $this->checkNumberIsGreaterThanRandomNumber($number);
        $this->checkNumberIsGuessed($number);
    }

    private function checkNumberIsCheat($number)
    {
        if ($number == $this->gameConfigService->getCheatNumber()) {
            throw new CheatException();
        }
    }

    private function checkNumberIsNotBetween($number)
    {
        $min = $this->gameConfigService->getMinNumber();
        $max = $this->gameConfigService->getMaxNumber();
        if ($number < $min || $number > $max) {
            return new NotInRangeException($number, $min, $max);
        }
    }

    private function checkNoEnoughAttempts()
    {
        if ($this->gameRepository->getRemainingAttempts() <= 1) {
            $this->gameRepository->setGameOver();
            return new NoEnoughAttemtsException();
        }
    }

    private function checkNumberIsLowerThanRandomNumber($number)
    {
        if ($number < $this->gameRepository->getRandomNumber()) {
            $this->gameRepository->decreaseRemainingAttempts();
            return new LowerThanRandomException($number);
        }
    }

    private function checkNumberIsGreaterThanRandomNumber($number)
    {
        if ($number > $this->gameRepository->getRandomNumber()) {
            $this->gameRepository->decreaseRemainingAttempts();
            return new GratherThanRandomException($number);
        }
    }

    private function checkNumberIsGuessed($number)
    {
        if ($number == $this->gameRepository->getRandomNumber()) {
            $this->gameRepository->setGameSuccess();
        }
    }
}
