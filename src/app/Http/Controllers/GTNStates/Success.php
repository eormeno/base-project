<?php

namespace App\Http\Controllers\GTNStates;

use App\FSM\FSMContext;
use App\Http\Controllers\GTNStates\GTNState;

class Success extends GTNState
{
    public function handleRequest(FSMContext $context, $event = null, $data = null)
    {
        $context->setValue('message', __('guess-the-number.success'));
        $context->setState(new AskingForPlayAgain());
    }
}
