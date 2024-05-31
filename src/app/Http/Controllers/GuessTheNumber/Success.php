<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Success extends StateAbstractImpl
{
    public string $notification = "";

    public function onEnter(): void
    {
        $this->notification = $this->context->messageService->successMessage();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
