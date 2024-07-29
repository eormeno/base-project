<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\Traits\DebugHelper;

class ShowingClue extends StateAbstractImpl
{
    use DebugHelper;
    public string $title = "";
    public string $goodLuck = "";
    public array $clues = [];
    public string $yes_i_accept_the_challenge = "";
    public string $another_challenge = "";

    public function onReload(): void
    {
        $this->onEnter();
    }

    public function onRefresh(): void
    {
        $this->title = $this->context->messageService->title();
        $this->goodLuck = $this->context->messageService->goodLuck();
        $this->clues = $this->context->messageService->clues();
        $this->another_challenge = $this->context->messageService->anotherChallenge();
        $this->yes_i_accept_the_challenge = $this->context->messageService->yesIAcceptTheChallenge();
    }

    public function onWantToPlayEvent()
    {
        return Playing::StateClass();
    }

    public function onAnotherChallengeEvent()
    {
        return Preparing::StateClass();
    }
}
