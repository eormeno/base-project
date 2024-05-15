<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class AskingToPlay extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $context->min_number = Globals::MIN_NUMBER;
        $context->max_number = Globals::MAX_NUMBER;
        $context->random_number = 0; // 0 means not set yet
        $context->remaining_attempts = Globals::MAX_ATTEMPTS;
        $context->message = '';
        if ($event == 'want_to_play') {
            $context->setState(new Preparing());
        }
    }
}
