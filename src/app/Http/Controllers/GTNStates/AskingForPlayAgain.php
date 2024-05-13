<?php

namespace App\Http\Controllers\GTNStates;

use App\FSM\FSMContext;

class AskingForPlayAgain extends GTNState
{
    public function handleRequest(FSMContext $context, $event = null, $data = null)
    {

    }
}
