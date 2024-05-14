<?php

namespace App\Http\Controllers\GTNStates;

use App\FSM\FSMContext;
use App\Http\Controllers\GTNGlobals;
use App\Http\Controllers\GTNStates\GTNState;

class Preparing extends GTNState
{
    public function handleRequest(FSMContext $context, $event = null, $data = null)
    {
        $context->setValue('random_number', rand(GTNGlobals::MIN_NUMBER, GTNGlobals::MAX_NUMBER));
        $context->setValue('remaining_attempts', GTNGlobals::MAX_ATTEMPTS);
        $context->setValue('message', '');
        $context->setState(new AskingToPlay());
    }
}
