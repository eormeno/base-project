<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MythicTreasureQuestGame;
use App\Services\AbstractServiceComponent;
use App\GameModels\MythicTreasureQuest\Map;

class GameRepository extends AbstractServiceComponent
{
    public function createEmptyNewGame(): void
    {
        MythicTreasureQuestGame::factory()->for(auth()->user())->create();
    }

    public function getGame(): MythicTreasureQuestGame
    {
        if (!auth()->user()->mythicTreasureQuestGames()->exists()) {
            $this->createEmptyNewGame();
        }
        return auth()->user()->mythicTreasureQuestGames;
    }

    public function getMap(): Map
    {
        $game = $this->getGame();
        $json = $game->map;
        $map = Map::fromJson($json, 8, 8);
        // $game->map = $map->jsonSerialize();
        // $game->save();
        return $map;
    }

}
