<?php

namespace App\Http\Controllers\GTNStates;

use App\FSM\FSMContext;

class GameOver extends GTNState
{
    public function handleRequest(FSMContext $context, $event = null, $data = null)
    {

    }
}
