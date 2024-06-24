<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MythicTreasureQuestGame;
use App\Models\MythicTreasureQuest\Tile;
use App\Services\AbstractServiceComponent;

class GameService extends AbstractServiceComponent
{
    public function getGame(): MythicTreasureQuestGame
    {
        return $this->gameRepository->getGame();
    }

    public function revealTile(Tile $tile)
    {
        $this->sendEvent($tile, 'reveal');
    }
}
