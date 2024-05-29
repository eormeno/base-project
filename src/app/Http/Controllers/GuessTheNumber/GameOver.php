<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class GameOver extends StateAbstractImpl
{
    use Messages\GameOverMessages;
    public string $notification = "";

    public function onEnter(): void
    {
        $this->notification = $this->gameOverMessage();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
