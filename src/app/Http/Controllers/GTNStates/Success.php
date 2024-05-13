<?php

namespace App\Http\Controllers\GTNStates;

use App\FSM\FSMContext;

class Success extends GTNState
{
    public function handleRequest(FSMContext $context, $event = null, $data = null)
    {

    }
}
