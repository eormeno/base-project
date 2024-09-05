<?php

namespace App\States\GuessTheNumber;

use App\FSM\AState;

class Initial extends AState
{
    public function passTo()
    {
        return AskingToPlay::StateClass();
    }
}
