<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class AskingToPlay extends StateAbstractImpl
{
    public string $description = "";
    public string $yes_i_accept_the_challenge = "";
    public array $ranking = [];

    public function onReload(): void
    {
        $this->onEnter();
    }

    public function onEnter(): void
    {
        $this->description = $this->context->messageService->welcomeMessage();
        $this->yes_i_accept_the_challenge = $this->context->messageService->yesIAcceptTheChallenge();
        $this->ranking = $this->context->gameRepository->getRanking();
    }

    public function onRefresh(): void
    {
        $this->onEnter();
    }

    public function onWantToPlayEvent()
    {
        $this->toast("Let's play!", 4000, "debug");
        //return Preparing::StateClass();
    }
}
