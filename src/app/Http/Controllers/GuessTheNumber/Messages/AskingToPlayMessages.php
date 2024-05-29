<?php

namespace App\Http\Controllers\GuessTheNumber\Messages;

trait AskingToPlayMessages
{
    public function welcomeMessage()
    {
        return __('guess-the-number.description', [
            'user_name' => auth()->user()->name,
            'remaining_attemts' => $this->context->remaining_attempts,
            'min_number' => $this->context->min_number,
            'max_number' => $this->context->max_number,
        ]);
    }
}
