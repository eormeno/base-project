<?php

namespace App\Http\Controllers\GuessTheNumber\Messages;

trait GameOverMessages
{
    public function gameOverMessage()
    {
        return __('guess-the-number.game-over', [
            'user_name' => $this->context->user_name
        ]);
    }
}
