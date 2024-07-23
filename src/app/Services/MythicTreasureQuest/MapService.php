<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MtqMap;
use App\Models\MtqGame;
use App\Models\MtqTile;
use App\Services\AbstractServiceComponent;

class MapService extends AbstractServiceComponent
{
    private MtqGame $castedGame;

    public function getMap2(): MtqMap
    {
        $this->castedGame = $this->gameRepository->getGame2();
        return $this->castedGame->mtqMaps()->first();
    }

    public function isValid(int $x, int $y): bool
    {
        $map = $this->getMap2();
        return $x >= 0 && $x < $map->width && $y >= 0 && $y < $map->height;
    }

    public function getMap2Tiles() : array
    {
        $map = $this->getMap2();
        return $map->tiles()->get()->all();
    }

    public function getTile(int $x, int $y): MtqTile
    {
        $map = $this->getMap2();
        return $map->tiles()->where('x', $x)->where('y', $y)->first();
    }

}
