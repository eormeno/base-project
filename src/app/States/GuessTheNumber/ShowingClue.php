<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class ShowingClue extends StateAbstractImpl
{
    public string $title = "";
    public string $goodLuck = "";
    public array $clues = [];

    public function onEnter(): void
    {
        $this->title = $this->context->messageService->title();
        $this->goodLuck = $this->context->messageService->goodLuck();
        $this->clues = $this->context->messageService->clues();
    }

    public function onWantToPlayEvent()
    {
        $this->context->setState(Playing::class);
    }
}
