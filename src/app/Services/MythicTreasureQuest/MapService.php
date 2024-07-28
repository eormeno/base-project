<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MtqMap;
use App\Models\MtqGame;
use App\Models\MtqTile;
use App\Helpers\MapHelper2;
use App\Services\AbstractServiceComponent;

class MapService extends AbstractServiceComponent
{
    private MtqGame $castedGame;

    public function getMap(): MtqMap
    {
        $this->castedGame = $this->gameRepository->getGame();
        return $this->castedGame->mtqMaps()->first();
    }

    public function restartTiles(): void
    {
        $map = $this->getMap();
        $currentTiles = $this->getMap2Tiles();
        $tiles = MapHelper2::generateMap($map->width, $map->height, 8);
        $i = 0;
        // copy the current tiles ids to the tiles array
        foreach ($currentTiles as $currentTile) {
            $tiles[$i]['id'] = $currentTile->id;
            $tiles[$i]['mtq_map_id'] = $currentTile->mtq_map_id;
            $i++;
        }

        $i = 0;
        foreach ($currentTiles as $currentTile) {
            MtqTile::where('id', $currentTile->id)->update($tiles[$i++]);
        }
    }

    public function isValid(int $x, int $y): bool
    {
        $map = $this->getMap();
        return $x >= 0 && $x < $map->width && $y >= 0 && $y < $map->height;
    }

    public function getMap2Tiles(): array
    {
        $map = $this->getMap();
        $tiles = $map->tiles()->get()->all();
        foreach ($tiles as $tile) {
            $tile->refresh();
        }
        return $tiles;
    }

    public function getTile(int $x, int $y): MtqTile
    {
        $map = $this->getMap();
        return $map->tiles()->where('x', $x)->where('y', $y)->first();
    }

}
