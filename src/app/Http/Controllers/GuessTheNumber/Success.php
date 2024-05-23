<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class Success extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $success_message = __('guess-the-number.success', ['user_name' => auth()->user()->name]);
        $this->delayedToast($success_message, 5000, 'success');
        $context->setState(new AskingForPlayAgain());
    }
}
