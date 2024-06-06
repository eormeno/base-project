<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Initial extends StateAbstractImpl
{
    public function passTo(): void
    {
        $this->context->setState(AskingToPlay::class);
    }
}
