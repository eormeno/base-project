<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class AskingToPlay extends StateAbstractImpl
{
    use Messages\AskingToPlayMessages;
    public string $description = "";

    public function onEnter(): void
    {
        $this->context->min_number = $this->context->gameConfigService->getMinNumber();
        $this->context->max_number = $this->context->gameConfigService->getMaxNumber();
        $this->context->remaining_attempts = $this->context->gameConfigService->maxAttempts();
        $this->description = $this->welcomeMessage();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'want_to_play') {
            $this->context->setState(Preparing::class);
        }
    }
}
