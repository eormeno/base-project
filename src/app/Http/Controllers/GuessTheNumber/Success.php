<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class Success extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $context->message = __(
            'guess-the-number.success',
            ['user_name' => auth()->user()->name]
        );
        $context->setState(new AskingForPlayAgain());
    }
}
