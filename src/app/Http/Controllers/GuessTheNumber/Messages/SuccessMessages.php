<?php

namespace App\Http\Controllers\GuessTheNumber\Messages;

trait SuccessMessages
{
    public function successMessage(): string
    {
        return __('guess-the-number.success', [
            'user_name' => $this->context->user_name
        ]);
    }
}
