<?php

namespace App\States\GuessTheNumber;

use App\FSM\AState;

class AskingToPlay extends AState
{
    public string $description = "";
    public string $yes_i_accept_the_challenge = "";
    public array $ranking = [];

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
        return Preparing::StateClass();
    }
}
