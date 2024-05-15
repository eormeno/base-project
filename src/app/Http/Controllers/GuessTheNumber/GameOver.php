<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class GameOver extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $context->message = __(
            'guess-the-number.game-over',
            ['user_name' => auth()->user()->name]
        );
        $context->setState(new AskingForPlayAgain());
    }
}
