<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Initial extends StateAbstractImpl
{
    public function handleRequest(?string $event = null, $data = null)
    {
        $this->context->setState(AskingToPlay::class);
    }
}
