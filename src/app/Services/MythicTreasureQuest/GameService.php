<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MythicTreasureQuestGame;
use App\Services\AbstractServiceComponent;

class GameService extends AbstractServiceComponent
{
    public function getGame(): MythicTreasureQuestGame
    {
        return $this->gameRepository->getGame();
    }
}
