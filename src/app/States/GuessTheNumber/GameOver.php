<?php

namespace App\States\GuessTheNumber;

use App\FSM\AState;

class GameOver extends AState
{
    public string $notification = "";
    public string $subtitle = "";

    public function onEnter(): void
    {
        $this->notification = $this->context->messageService->gameOverMessage();
        $this->subtitle = $this->context->messageService->gameOverSubtitle();
    }

    public function onPlayAgainEvent()
    {
        return Preparing::StateClass();
    }

    public function onExitEvent()
    {
        return AskingToPlay::StateClass();
    }
}
