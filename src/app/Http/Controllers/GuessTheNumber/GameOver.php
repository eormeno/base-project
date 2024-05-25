<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class GameOver extends StateAbstractImpl
{
    public function handleRequest(StateContextInterface $context, $event = null, $data = null)
    {
        $game_over_message = __('guess-the-number.game-over', ['user_name' => auth()->user()->name]);
        //$this->delayedToast($game_over_message, 5000, 'error');
        //$context->setState(new AskingForPlayAgain());
        $context->notification = $game_over_message;
        if ($event == 'play_again') {
            $context->setState(new Preparing());
        }
    }
}
