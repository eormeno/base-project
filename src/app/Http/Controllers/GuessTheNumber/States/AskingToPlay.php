<?php

namespace App\Http\Controllers\GuessTheNumber\States;

use App\FSM\StateAbstractImpl;

class AskingToPlay extends StateAbstractImpl
{
    public string $description = "";

    public function onEnter(): void
    {
        $this->description = $this->context->messageService->welcomeMessage();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'want_to_play') {
            $this->context->setState(Preparing::class);
        }
    }
}
