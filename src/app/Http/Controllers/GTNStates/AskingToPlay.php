<?php

namespace App\Http\Controllers\GTNStates;

use App\FSM\FSMContext;
use App\Http\Controllers\GTNStates\GTNState;

class AskingToPlay extends GTNState
{
    public function handleRequest(FSMContext $context, $event = null, $data = null)
    {
        if ($event == 'want_to_play') {
            $context->setState(new Playing());
        }
    }
}
