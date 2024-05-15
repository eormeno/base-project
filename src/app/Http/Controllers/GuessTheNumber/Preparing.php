<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class Preparing extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $context->random_number = rand(Globals::MIN_NUMBER, Globals::MAX_NUMBER);
        // calculate the remaining attempts based on the log in 2 base of the difference between max and min
        $context->remaining_attempts = ceil(log($context->max_number - $context->min_number, 2));
        $context->message = '';
        $context->setState(new Playing());
    }
}
