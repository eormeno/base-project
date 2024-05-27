<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class GameOver extends StateAbstractImpl
{
    public function handleRequest(?string $event = null, $data = null)
    {
        $game_over_message = __('guess-the-number.game-over', ['user_name' => auth()->user()->name]);
        //$this->delayedToast($game_over_message, 5000, 'error');
        //$context->setState(new AskingForPlayAgain());
        $this->context->notification = $game_over_message;
        if ($event == 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
