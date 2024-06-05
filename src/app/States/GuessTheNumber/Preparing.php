<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Preparing extends StateAbstractImpl
{
    public function onEnter(): void
    {
        $this->context->gameService->startGame();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        $this->context->setState(ShowingClue::class);
    }
}
