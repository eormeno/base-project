<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Success extends StateAbstractImpl
{
    public function handleRequest(?string $event = null, $data = null)
    {
        $success_message = __('guess-the-number.success', ['user_name' => auth()->user()->name]);
        $this->context->notification = $success_message;
        if ($event == 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
