<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MythicTreasureQuestGame;
use App\Models\MythicTreasureQuest\Tile;
use App\Services\AbstractServiceComponent;

class GameService extends AbstractServiceComponent
{
    private const DIRECTIONS = [[-1, -1], [0, -1], [1, -1], [-1, 0], [1, 0], [-1, 1], [0, 1], [1, 1]];
    private array $forbiddenTiles = [];
    public function getGame(): MythicTreasureQuestGame
    {
        return $this->gameRepository->getGame();
    }

    public function revealTile(Tile $tile)
    {
        if (array_key_exists($tile->getId(), $this->forbiddenTiles)) {
            return;
        }
        $this->forbiddenTiles[$tile->getId()] = true;
        $map = $tile->getMap();
        $intX = $tile->getX();
        $intY = $tile->getY();
        foreach (self::DIRECTIONS as $dir) {
            $newX = $intX + $dir[0];
            $newY = $intY + $dir[1];
            if ($newX >= 0 && $newX < $map->getWidth() && $newY >= 0 && $newY < $map->getHeight()) {
                $newTile = $map->getTile($newX, $newY);
                if ($newTile->getHasTrap()){
                    $this->forbiddenTiles[$newTile->getId()] = true;
                }
                if ($newTile->getTrapsAround() === 0) {
                    $this->revealTile($newTile);
                }
                if ($newTile->getTrapsAround() > 0) {
                    $this->forbiddenTiles[$newTile->getId()] = true;
                    $this->sendEvent($newTile, 'reveal');
                }
            }
        }

        // if ($intY > 0 && $intX > 0) {
        //     $this->revealTile($map->getTile($intX - 1, $intY - 1));
        // }

        $this->sendEvent($tile, 'reveal');
    }
}
