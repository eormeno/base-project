<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class Preparing extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $context->random_number = rand(Globals::MIN_NUMBER, Globals::MAX_NUMBER);
        $context->remaining_attempts= Globals::MAX_ATTEMPTS;
        $context->message = '';
        $context->setState(new Playing());
    }
}
