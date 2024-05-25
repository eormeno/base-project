<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class Success extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $success_message = __('guess-the-number.success', ['user_name' => auth()->user()->name]);
        $context->notification = $success_message;
        if ($event == 'play_again') {
            $context->setState(new Preparing());
        }
        //$this->delayedToast($success_message, 5000, 'success');
    }
}
