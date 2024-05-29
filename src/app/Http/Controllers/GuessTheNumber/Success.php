<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Success extends StateAbstractImpl
{
    use Messages\SuccessMessages;
    public string $notification = "";

    public function onEnter(): void
    {
        $this->notification = $this->successMessage();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
