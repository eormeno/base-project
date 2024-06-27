<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MythicTreasureQuestGame;
use App\Models\MythicTreasureQuest\Tile;
use App\Services\AbstractServiceComponent;

class GameService extends AbstractServiceComponent
{
    private const DIRECTIONS = [[-1, -1], [0, -1], [1, -1], [1, 0], [1, 1], [0, 1], [-1, 1], [-1, 0]];
    private array $testedTiles = [];
    private array $availableTiles = [];


    public function getGame(): MythicTreasureQuestGame
    {
        return $this->gameRepository->getGame();
    }

    public function revealAll()
    {
        $map = $this->gameRepository->getMap();
        foreach ($map->getTiles() as $tile) {
            if ($tile->getHasTrap()) {
                $this->sendEvent($tile, 'reveal');
            }
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
            if ($tile->getHasTrap() || $tile->isRevealed() || $tile->isMarkedAsClue()) {
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

    public function showClue(): bool
    {
        $this->fillAvailableTiles();
        if (empty($this->availableTiles)) {
            return false;
        }
        $tile = $this->availableTiles[array_rand($this->availableTiles)];
        $tile->setMarkedAsClue(true);
        $this->inventoryRepository->saveInventory();
        return true;
    }

    public function revealTile(Tile $tile) {
        if ($tile->getTrapsAround() > 0) {
            $this->sendEvent($tile, 'reveal');
            return;
        }
        $this->_revealTile($tile);
    }

    private function _revealTile(Tile $tile)
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
                    $this->_revealTile($newTile);
                }
            }
        }
        $this->sendEvent($tile, 'reveal');
    }

    private function isTileTested(Tile $tile): bool
    {
        return array_key_exists($tile->getId(), $this->testedTiles);
    }

    private function setTileAsTested(Tile $tile): void
    {
        $this->testedTiles[$tile->getId()] = true;
    }

}
