<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class Initial extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $context->setState(new AskingToPlay());
    }
}
