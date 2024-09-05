<?php

namespace App\States\GuessTheNumber;

use App\FSM\AState;

class Preparing extends AState
{
    public function onEnter(): void
    {
        $this->context->gameService->startGame();
    }

    public function passTo()
    {
        return ShowingClue::StateClass();
    }
}
