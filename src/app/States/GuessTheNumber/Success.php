<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Success extends StateAbstractImpl
{
    public string $notification = "";
    public string $subtitle = "";

    public function onEnter(): void
    {
        $this->notification = $this->context->messageService->successMessage();
        $this->subtitle = $this->context->messageService->successSubtitleMessage();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
