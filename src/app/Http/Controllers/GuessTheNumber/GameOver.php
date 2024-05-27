<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\FSM\StateContextInterface;

class GameOver extends StateAbstractImpl
{
    public string $notification = "";
    public function handleRequest(?string $event = null, $data = null)
    {
        $game_over_message = __('guess-the-number.game-over', ['user_name' => auth()->user()->name]);
        $this->notification = $game_over_message;
        if ($event == 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
