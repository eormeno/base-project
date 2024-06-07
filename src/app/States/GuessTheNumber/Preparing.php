<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Preparing extends StateAbstractImpl
{
    public function onEnter(bool $restoring): void
    {
        $this->context->gameService->startGame();
    }

    public function passTo(): void
    {
        $this->context->setState(ShowingClue::class);
    }
}
