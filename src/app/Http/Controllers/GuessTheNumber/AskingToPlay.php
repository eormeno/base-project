<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class AskingToPlay extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $context->setValue('min_number', GTNGlobals::MIN_NUMBER);
        $context->setValue('max_number', GTNGlobals::MAX_NUMBER);
        $context->setValue('random_number', 0); // 0 means not set yet
        $context->setValue('remaining_attempts', GTNGlobals::MAX_ATTEMPTS);
        $context->setValue('message', '');
        if ($event == 'want_to_play') {
            $context->setState(new Preparing());
        }
    }
}
