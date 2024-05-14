<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class Preparing extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $context->setValue('random_number', rand(GTNGlobals::MIN_NUMBER, GTNGlobals::MAX_NUMBER));
        $context->setValue('remaining_attempts', GTNGlobals::MAX_ATTEMPTS);
        $context->setValue('message', '');
        $context->setState(new Playing());
    }
}
