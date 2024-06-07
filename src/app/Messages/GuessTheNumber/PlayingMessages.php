<?php

namespace App\Messages\GuessTheNumber;

use App\Services\GuessTheNumber\AbstractComponent;

class PlayingMessages extends AbstractComponent
{
    public function greaterMessage($number)
    {
        return __('guess-the-number.greater', [
            'number' => $number
        ]);
    }

    public function lowerMessage($number)
    {
        return __('guess-the-number.lower', [
            'number' => $number
        ]);
    }

    public function cheatMessage()
    {
        $game = $this->gameRepository->getGame();
        return __('guess-the-number.cheat', [
            'random_number' => $game->random_number
        ]);
    }

    public function invalidNumberMessage()
    {
        return __('guess-the-number.invalid_number', [
            'min_number' => $this->gameConfigService->getMinNumber(),
            'max_number' => $this->gameConfigService->getMaxNumber()
        ]);
    }

    public function remainingAttemptsMessage()
    {
        $game = $this->gameRepository->getGame();
        $remaining_attempts = $game->remaining_attempts;

        if ($remaining_attempts == 1) {
            return __('guess-the-number.last_attempt');
        }
        if ($remaining_attempts == $this->gameConfigService->getMaxAttempts()) {
            return __('guess-the-number.starting_attempts', [
                'remaining_attemts' => $remaining_attempts
            ]);
        }
        if ($remaining_attempts <= $this->gameConfigService->getHalfAttempts()) {
            return __('guess-the-number.remaining_half', [
                'remaining_attemts' => $remaining_attempts
            ]);
        }
        return __('guess-the-number.remaining', [
            'remaining_attemts' => $remaining_attempts
        ]);
    }
}
