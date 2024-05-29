<?php

namespace App\Http\Controllers\GuessTheNumber\Messages;

use App\Http\Controllers\GuessTheNumber\Globals;

trait PlayingMessages
{
    private function greaterMessage($number)
    {
        return __('guess-the-number.greater', ['number' => $number]);
    }

    private function lowerMessage($number)
    {
        return __('guess-the-number.lower', ['number' => $number]);
    }

    private function cheatMessage($random_number)
    {
        return __('guess-the-number.cheat', ['random_number' => $random_number]);
    }

    private function invalidNumberMessage()
    {
        return __('guess-the-number.invalid_number', [
            'min_number' => Globals::MIN_NUMBER,
            'max_number' => Globals::MAX_NUMBER
        ]);
    }

    private function remainingAttemptsMessage($remaining_attempts)
    {
        if ($remaining_attempts == 1) {
            return __('guess-the-number.last_attempt');
        }
        if ($remaining_attempts == Globals::maxAttempts()) {
            return __('guess-the-number.starting_attempts', ['remaining_attemts' => $remaining_attempts]);
        }
        if ($remaining_attempts <= Globals::halfAttempts()) {
            return __('guess-the-number.remaining_half', ['remaining_attemts' => $remaining_attempts]);
        }
        return __('guess-the-number.remaining', ['remaining_attemts' => $remaining_attempts]);
    }

}
