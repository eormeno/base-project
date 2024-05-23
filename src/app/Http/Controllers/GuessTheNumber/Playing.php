<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class Playing extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        if ($event == 'guess') {
            if ($context->remaining_attempts <= 1) {
                $context->setState(new GameOver());
                return;
            }
            $random_number = $context->random_number;
            $remaining_attempts = $context->remaining_attempts;
            $grather_message = __('guess-the-number.greater', ['number' => $data]);
            $lower_message = __('guess-the-number.lower', ['number' => $data]);
            if ($data < $random_number) {
                //$context->message = __('guess-the-number.greater', ['number' => $data]);
                $this->delayedToast($grather_message, 4000, "warning");
            } elseif ($data > $random_number) {
                //$context->message = __('guess-the-number.lower', ['number' => $data]);
                $this->delayedToast($lower_message, 4000, "warning");
            } else {
                $context->setState(new Success());
            }
            $remaining_attempts--;
            $context->remaining_attempts = $remaining_attempts;
        }
    }
}
