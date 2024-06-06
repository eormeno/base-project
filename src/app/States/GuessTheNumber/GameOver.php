<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class GameOver extends StateAbstractImpl
{
    public string $notification = "";
    public string $subtitle = "";

    public function onEnter(): void
    {
        $this->notification = $this->context->messageService->gameOverMessage();
        $this->subtitle = $this->context->messageService->gameOverSubtitle();
        //$this->context->gameService->endGame();
    }

    public function onPlayAgainEvent()
    {
        $this->context->setState(Preparing::class);
    }

    public function onExitEvent()
    {
        $this->context->setState(AskingToPlay::class);
    }
}
