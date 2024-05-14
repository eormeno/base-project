<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class Playing extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        if ($event == 'guess') {
            $random_number = $context->random_number;
            $remaining_attempts = $context->remaining_attempts;
            if ($data < $random_number) {
                $context->message = __('guess-the-number.greater', ['number' => $data]);
            } elseif ($data > $random_number) {
                $context->message = __('guess-the-number.lower', ['number' => $data]);
            } else {
                $context->setState(new Success());
            }
            if ($remaining_attempts == 0) {
                $context->setState(new GameOver());
            }
            $remaining_attempts--;
            $context->remaining_attempts = $remaining_attempts;
        }
    }
}
