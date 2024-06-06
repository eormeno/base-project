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

    public function onWantToPlayEvent()
    {
        $this->context->setState(Preparing::class);
    }
}
