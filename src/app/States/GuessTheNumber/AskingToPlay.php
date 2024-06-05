<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class AskingToPlay extends StateAbstractImpl
{
    public string $description = "";
    public array $ranking = [];

    public function onEnter(): void
    {
        $this->description = $this->context->messageService->welcomeMessage();
        $this->ranking = $this->context->gameRepository->getRanking();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'want_to_play') {
            $this->context->setState(Preparing::class);
        }
    }
}
