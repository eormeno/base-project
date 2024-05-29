<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Playing extends StateAbstractImpl
{
    public string $notification = "";
    public function handleRequest(?string $event = null, $data = null)
    {
        $remaining_attempts = $this->context->remaining_attempts;
        $this->notification = $this->remainingAttemptsMessage($remaining_attempts);
        if ($event == 'guess') {
            $number = $data['number'] ?? -1;
            $random_number = $this->context->random_number;
            if ($number == 55555) {
                $this->toast("The secret number is $random_number", 4000, "success");
                return;
            }
            if ($number < Globals::MIN_NUMBER || $number > Globals::MAX_NUMBER) {
                $this->toast($this->invalidNumberMessage(), 4000, "error");
                return;
            }
            if ($remaining_attempts <= 1) {
                $this->context->setState(GameOver::class);
                return;
            }
            $grather_message = __('guess-the-number.greater', ['number' => $number]);
            $lower_message = __('guess-the-number.lower', ['number' => $number]);
            if ($number < $random_number) {
                $this->toast($grather_message, 4000, "warning");
            } elseif ($number > $random_number) {
                $this->toast($lower_message, 4000, "warning");
            } else {
                $this->context->setState(Success::class);
            }
            $remaining_attempts--;
            $this->context->remaining_attempts = $remaining_attempts;
            $this->notification = $this->remainingAttemptsMessage($remaining_attempts);
        }
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
