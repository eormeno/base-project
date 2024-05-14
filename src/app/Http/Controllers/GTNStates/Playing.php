<?php

namespace App\Http\Controllers\GTNStates;

use App\FSM\FSMContext;
use App\Http\Controllers\GTNStates\GTNState;

class Playing extends GTNState
{
    public function handleRequest(FSMContext $context, $event = null, $data = null)
    {
        if ($event == 'guess') {
            $random_number = $context->getValue('random_number');
            $remaining_attempts = $context->getValue('remaining_attempts');
            if ($data < $random_number) {
                $context->setValue('message', __('guess-the-number.greater'));
            } elseif ($data > $random_number) {
                $context->setValue('message', __('guess-the-number.lower'));
            } else {
                $context->setState(new Success());
            }
            if ($remaining_attempts == 0) {
                $context->setState(new GameOver());
            }
            $remaining_attempts--;
            $context->setValue('remaining_attempts', $remaining_attempts);
        }
    }
}
