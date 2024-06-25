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

    public function revealAll()
    {
        $map = $this->gameRepository->getMap();
        foreach ($map->getTiles() as $tile) {
            $this->sendEvent($tile, 'reveal');
        }
    }

    public function revealTile(Tile $tile)
    {
        if ($this->isTileTested($tile)) {
            return;
        }
        $this->setTileAsTested($tile);
        $map = $tile->getMap();
        $intX = $tile->getX();
        $intY = $tile->getY();
        foreach (self::DIRECTIONS as $dir) {
            $newX = $intX + $dir[0];
            $newY = $intY + $dir[1];
            if ($map->isValid($newX, $newY)) {
                $newTile = $map->getTile($newX, $newY);
                if ($this->isTileTested($newTile)) {
                    continue;
                }
                if ($newTile->getHasTrap() || $newTile->isRevealed()) {
                    $this->setTileAsTested($newTile);
                    continue;
                }
                if ($newTile->getTrapsAround() === 0) {
                    $this->revealTile($newTile);
                }
                if ($newTile->getTrapsAround() > 0) {
                    $this->setTileAsTested($newTile);
                    $this->sendEvent($newTile, 'reveal');
                }
            }
        }
        $this->sendEvent($tile, 'reveal');
    }

    private function isTileTested(Tile $tile): bool
    {
        return array_key_exists($tile->getId(), $this->forbiddenTiles);
    }

    private function setTileAsTested(Tile $tile): void
    {
        $this->forbiddenTiles[$tile->getId()] = true;
    }

}
