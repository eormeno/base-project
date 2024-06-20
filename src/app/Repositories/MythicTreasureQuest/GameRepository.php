<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MythicTreasureQuestGame;
use App\Services\AbstractServiceComponent;
use App\Models\MythicTreasureQuest\Map;

class GameRepository extends AbstractServiceComponent
{
    private Map $map;

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
        $this->map = Map::fromJson($json, 8, 8); // phpcs:ignore
        return $this->map;
    }

    public function saveMap(): void
    {
        $game = $this->getGame();
        $game->map = $this->map->jsonSerialize();
        $game->save();
    }

}
