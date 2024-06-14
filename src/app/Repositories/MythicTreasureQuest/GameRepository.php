<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MythicTreasureQuest\Map;
use App\Models\MythicTreasureQuestGame;
use App\Models\MythicTreasureQuest\Tile;
use App\Services\AbstractServiceComponent;

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
        // try to parse the json map file
        $map = Map::fromJson($game->map);
        return $map;
    }

    private function generate(): void
    {
        $this->tiles = [];
        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                $this->tiles[] = new Tile(['id' => $x + $y * $this->width]);
            }
        }
    }


}
