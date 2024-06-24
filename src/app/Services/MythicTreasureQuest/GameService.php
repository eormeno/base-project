<?php

namespace App\Services\MythicTreasureQuest;

use App\FSM\StateContextInterface;
use App\Models\MythicTreasureQuestGame;
use App\Models\MythicTreasureQuest\Tile;
use App\Services\AbstractServiceComponent;
use App\States\Tile\Revealed;

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

    public function revealTile2(Tile $tile)
    {
    }

}
