<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class GameOver extends StateAbstractImpl
{
    public string $notification = "";
    public string $subtitle = "";

    public function onEnter(bool $restoring): void
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
