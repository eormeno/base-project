<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Success extends StateAbstractImpl
{
    public string $notification = "";
    public string $subtitle = "";
    public string $current_score = "";
    public string $historic_score = "";

    public function onReload(): void
    {
        $this->onEnter();
    }

    public function onEnter(): void
    {
        $this->notification = $this->context->messageService->successMessage();
        $this->subtitle = $this->context->messageService->successSubtitleMessage();
        $this->current_score = $this->context->messageService->currentScoreMessage();
        $this->historic_score = $this->context->messageService->historicScoreMessage();
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
