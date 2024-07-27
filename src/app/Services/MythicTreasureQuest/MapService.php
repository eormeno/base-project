<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MtqMap;
use App\Models\MtqGame;
use App\Models\MtqTile;
use App\Services\AbstractServiceComponent;

class MapService extends AbstractServiceComponent
{
    private MtqGame $castedGame;

    public function getMap(): MtqMap
    {
        $this->castedGame = $this->gameRepository->getGame();
        return $this->castedGame->mtqMaps()->first();
    }

    public function isValid(int $x, int $y): bool
    {
        $map = $this->getMap();
        return $x >= 0 && $x < $map->width && $y >= 0 && $y < $map->height;
    }

    public function getMap2Tiles() : array
    {
        $map = $this->getMap();
        return $map->tiles()->get()->all();
    }

    public function getTile(int $x, int $y): MtqTile
    {
        $map = $this->getMap();
        return $map->tiles()->where('x', $x)->where('y', $y)->first();
    }

}
