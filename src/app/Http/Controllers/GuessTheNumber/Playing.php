<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Playing extends StateAbstractImpl
{
    use Messages\PlayingMessages;
    use Logics\PlayingLogics;

    public string $notification = "";

    public function handleRequest(?string $event = null, $data = null)
    {
        $remaining_attempts = $this->context->remaining_attempts;
        $this->notification = $this->remainingAttemptsMessage($remaining_attempts);
        if ($event == 'guess') {
            $number = $data['number'] ?? -1;
            $random_number = $this->context->random_number;
            if ($this->isNumberCheat($number, $random_number))
                return;
            if ($this->isNumberNotBetween($number))
                return;
            if ($this->noEnoughAttempts($remaining_attempts, GameOver::class))
                return;
            if ($this->isNumberGuessed($number, $random_number, Success::class))
                return;
            $this->isNumberLowerThanRandomNumber($number, $random_number);
            $this->isNumberGreaterThanRandomNumber($number, $random_number);
            $remaining_attempts--;
            $this->context->remaining_attempts = $remaining_attempts;
            $this->notification = $this->remainingAttemptsMessage($remaining_attempts);
        }
    }
}
