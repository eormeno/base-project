<?php

namespace App\Http\Controllers\GuessTheNumber\States;

use App\FSM\StateAbstractImpl;

class GameOver extends StateAbstractImpl
{
    public string $notification = "";
    public string $subtitle = "";

    public function onEnter(): void
    {
        $this->notification = $this->context->messageService->gameOverMessage();
        $this->subtitle = $this->context->messageService->gameOverSubtitle();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
