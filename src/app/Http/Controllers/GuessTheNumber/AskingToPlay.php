<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class AskingToPlay extends StateAbstractImpl
{
    use Messages\AskingToPlayMessages;
    public string $description = "";

    public function onEnter(): void
    {
        $this->context->min_number = Globals::MIN_NUMBER;
        $this->context->max_number = Globals::MAX_NUMBER;
        $this->context->remaining_attempts = Globals::maxAttempts();
        $this->description = $this->welcomeMessage();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'want_to_play') {
            $this->context->setState(Preparing::class);
        }
    }
}
