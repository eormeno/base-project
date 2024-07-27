<?php

namespace App\States\MythicTreasureQuest;
use App\Traits\DebugHelper;

class GameOver extends APlayingStates
{
    use DebugHelper;

    public function onRefresh(): void
    {
        parent::onRefresh();
        $this->log("GameOver::onRefresh");
    }

    public function onPlayAgainEvent()
    {
        $this->context->gameRepository->restartGame();
        return Initial::StateClass();
    }
}
