<?php

namespace App\Http\Controllers\GTNStates;

use App\FSM\FSMContext;
use App\Http\Controllers\GTNStates\GTNState;

class AskingForPlayAgain extends GTNState
{
    public function handleRequest(FSMContext $context, $event = null, $data = null)
    {
        if ($event === 'play_again') {
            $context->setState(new Preparing());
        }
    }
}
