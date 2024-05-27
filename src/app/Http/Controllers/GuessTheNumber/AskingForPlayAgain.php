<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class AskingForPlayAgain extends StateAbstractImpl
{
    public function handleRequest($event = null, $data = null)
    {
        if ($event === 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
