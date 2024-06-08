<?php

namespace App\States\GuessTheNumber;

use ReflectionClass;
use App\FSM\StateAbstractImpl;

class Preparing extends StateAbstractImpl
{
    public function onEnter(bool $restoring): void
    {
        $this->context->gameService->startGame();
    }

    public function passTo(): ReflectionClass
    {
        return ShowingClue::StateClass();
    }
}
