<?php

namespace App\States\MythicTreasureQuest;

class GameOver extends Playing
{
    public function onEnter(): void
    {
        parent::onEnter();
        $this->errorToast('Game Over');
    }

    public function onPlayAgainEvent()
    {
        $this->context->gameRepository->restartGame();
        $this->requireRefresh();
        return Initial::StateClass();
    }
}
