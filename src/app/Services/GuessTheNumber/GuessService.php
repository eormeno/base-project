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
        protected MessageService $messageService,
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

    private function checkNumberIsCheat($number)
    {
        if ($number == $this->gameConfigService->getCheatNumber()) {
            $this->updateRemainingAttempts(1);
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
        $game = $this->gameRepository->getGame();
        if ($game->remaining_attempts <= 1) {
            throw new GameOverException();
        }
    }

    private function checkNumberIsLowerThanRandomNumber($number)
    {
        $game = $this->gameRepository->getGame();

        if ($number < $game->random_number) {
            $this->decreaseRemainingAttempts();
            throw new FailException($this->messageService->greaterMessage($number));
        }
    }

    private function checkNumberIsGreaterThanRandomNumber($number)
    {
        $game = $this->gameRepository->getGame();
        if ($number > $game->random_number) {
            $this->decreaseRemainingAttempts();
            throw new FailException($this->messageService->lowerMessage($number));
        }
    }

    private function checkNumberIsGuessed($number)
    {
        $game = $this->gameRepository->getGame();
        if ($number == $game->random_number) {
            throw new SuccessException();
        }
    }
}
