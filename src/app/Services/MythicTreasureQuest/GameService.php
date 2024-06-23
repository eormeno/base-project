<?php

namespace App\Services\MythicTreasureQuest;

use App\FSM\StateContextInterface;
use App\Models\MythicTreasureQuestGame;
use App\Services\AbstractServiceComponent;

class GameService extends AbstractServiceComponent
{
    public function getGame(): MythicTreasureQuestGame
    {
        return $this->gameRepository->getGame();
    }

    public function revealTile(StateContextInterface $context)
    {
        $this->sendEvent($context, 'reveal');
    }

}
