<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MythicTreasureQuestGame;
use App\Models\MythicTreasureQuest\Tile;
use App\Services\AbstractServiceComponent;

class GameService extends AbstractServiceComponent
{
    private const DIRECTIONS = [[-1, -1], [0, -1], [1, -1], [1, 0], [1, 1], [0, 1], [-1, 1], [-1, 0]];
    private array $forbiddenTiles = [];
    private array $availableTiles = [];


    public function getGame(): MythicTreasureQuestGame
    {
        return $this->gameRepository->getGame();
    }

    public function revealAll()
    {
        $map = $this->gameRepository->getMap();
        foreach ($map->getTiles() as $tile) {
            if ($tile->isRevealed()) {
                continue;
            }
            $this->sendEvent($tile, 'reveal');
        }
    }

    private function fillAvailableTiles()
    {
        if (!empty($this->availableTiles)) {
            return;
        }
        $this->availableTiles = [];
        $map = $this->gameRepository->getMap();
        foreach ($map->getTiles() as $tile) {
            if ($tile->getHasTrap() || $tile->isRevealed()) {
                continue;
            }
            $this->availableTiles[] = $tile;
        }
    }

    private function isTileAvailable(Tile $tile): bool
    {
        $this->fillAvailableTiles();
        return in_array($tile, $this->availableTiles);
    }

    public function revealTile(Tile $tile)
    {
        if ($this->isTileTested($tile)) {
            return;
        }
        $this->setTileAsTested($tile);
        $this->fillAvailableTiles();
        $map = $tile->getMap();
        $intX = $tile->getX();
        $intY = $tile->getY();
        foreach (self::DIRECTIONS as $dir) {
            $newX = $intX + $dir[0];
            $newY = $intY + $dir[1];
            if ($map->isValid($newX, $newY)) {
                $newTile = $map->getTile($newX, $newY);
                if ($this->isTileAvailable($newTile)) {
                    if ($this->isTileTested($newTile)) {
                        continue;
                    }
                    if ($newTile->getTrapsAround() > 0) {
                        $this->setTileAsTested($newTile);
                        $this->sendEvent($newTile, 'reveal');
                        continue;
                    }
                    $this->revealTile($newTile);
                }
                // if ($this->isTileTested($newTile)) {
                //     continue;
                // }
                // if ($newTile->getHasTrap() || $newTile->isRevealed()) {
                //     $this->setTileAsTested($newTile);
                //     continue;
                // }
                // if ($newTile->getTrapsAround() === 0) {
                //     $this->revealTile($newTile);
                // }
                // if ($newTile->getTrapsAround() > 0) {
                //     $this->setTileAsTested($newTile);
                //     $this->sendEvent($newTile, 'reveal');
                //     $this->log('Revealed # ' . $newX . ' ' . $newY);
                // }
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
