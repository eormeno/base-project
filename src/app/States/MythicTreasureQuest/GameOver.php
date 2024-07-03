<?php

namespace App\States\MythicTreasureQuest;

class GameOver extends Playing
{
    public function onEnter(): void
    {
        $this->errorToast('Game Over');
        $this->requireRefresh();
    }

    public function onPlayAgainEvent()
    {
        $this->context->gameRepository->restartGame();
        $this->requireRefresh();
        return Initial::StateClass();
    }
}
