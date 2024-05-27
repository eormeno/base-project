<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class AskingToPlay extends StateAbstractImpl
{
    public string $description = "";

    public function onEnter(): void
    {
        $this->times_played = 0;
        $this->context->min_number = Globals::MIN_NUMBER;
        $this->context->max_number = Globals::MAX_NUMBER;
        $this->context->random_number = 0; // 0 means not set yet
        $this->context->notification = "";
        $this->context->remaining_attempts = Globals::maxAttempts();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        $this->description = __('guess-the-number.description', [
            'user_name' => auth()->user()->name,
            'remaining_attemts' => $this->context->remaining_attempts,
            'min_number' => $this->context->min_number,
            'max_number' => $this->context->max_number,
        ]);
        if ($event == 'want_to_play') {
            $this->context->setState(Preparing::class);
        }
    }
}
