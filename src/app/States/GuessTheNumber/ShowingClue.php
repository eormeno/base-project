<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class ShowingClue extends StateAbstractImpl
{
    public string $title = "";
    public string $goodLuck = "";
    public array $clues = [];

    public function __onEnter(): void
    {
        $this->title = $this->context->messageService->title();
        $this->goodLuck = $this->context->messageService->goodLuck();
        $this->clues = $this->context->messageService->clues();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        $this->__onEnter();
        if ($event == 'want_to_play') {
            $this->context->setState(Playing::class);
        }
    }
}
