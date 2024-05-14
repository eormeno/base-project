<?php

namespace App\Http\Controllers\GTNStates;

use App\FSM\FSMContext;
use App\FSM\FSMState;
use App\Http\Controllers\GTNGlobals;

class Initial extends GTNState
{
    public function handleRequest(FSMContext $context, $event = null, $data = null)
    {
        $context->setValue('min_number', GTNGlobals::MIN_NUMBER);
        $context->setValue('max_number', GTNGlobals::MAX_NUMBER);
        $context->setState(new Preparing());
    }
}
