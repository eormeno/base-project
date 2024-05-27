<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Playing extends StateAbstractImpl
{
    public function handleRequest(?string $event = null, $data = null)
    {
        $remaining_attempts = $this->context->remaining_attempts;
        $this->context->notification = $this->remainingAttemptsMessage($remaining_attempts);
        if ($event == 'guess') {
            if ($remaining_attempts <= 1) {
                $this->context->setState(GameOver::class);
                return;
            }
            $random_number = $this->context->random_number;
            $grather_message = __('guess-the-number.greater', ['number' => $data]);
            $lower_message = __('guess-the-number.lower', ['number' => $data]);
            if ($data < $random_number) {
                $this->delayedToast($grather_message, 4000, "warning");
            } elseif ($data > $random_number) {
                $this->delayedToast($lower_message, 4000, "warning");
            } else {
                $this->context->setState(Success::class);
            }
            $remaining_attempts--;
            $this->context->remaining_attempts = $remaining_attempts;
            $this->context->notification = $this->remainingAttemptsMessage($remaining_attempts);
        }
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
