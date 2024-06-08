<?php

namespace App\States\GuessTheNumber;

use ReflectionClass;
use App\FSM\StateAbstractImpl;

class Initial extends StateAbstractImpl
{
    public function passTo(): ReflectionClass
    {
        return AskingToPlay::StateClass();
    }
}
