<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class GameOver extends StateAbstractImpl
{
    public string $notification = "";

    public function onEnter(): void
    {
        $this->notification = $this->context->messageService->gameOverMessage();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
