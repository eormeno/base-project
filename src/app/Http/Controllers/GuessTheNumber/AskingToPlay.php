<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class AskingToPlay extends StateAbstractImpl
{
    private int $times_played = 0;

    public function handleRequest(?string $event = null, $data = null)
    {
        $this->times_played++;
        $this->context->min_number = Globals::MIN_NUMBER;
        $this->context->max_number = Globals::MAX_NUMBER;
        $this->context->random_number = 0; // 0 means not set yet
        $this->context->notification = "";
        $this->context->remaining_attempts = Globals::maxAttempts();
        $this->context->description = __('guess-the-number.description', [
            'user_name' => auth()->user()->name,
            'remaining_attemts' => $this->context->remaining_attempts,
            'min_number' => $this->context->min_number,
            'max_number' => $this->context->max_number,
        ]) . " $this->times_played";
        if ($event == 'want_to_play') {
            $this->context->setState(Preparing::class);
        }
    }
}
