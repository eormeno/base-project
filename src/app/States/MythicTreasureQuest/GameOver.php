<?php

namespace App\States\MythicTreasureQuest;

class GameOver extends APlayingStates
{
    public function onPlayAgainEvent()
    {
        $this->context->gameRepository->restartGame();
        return Initial::StateClass();
    }
}
