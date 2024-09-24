<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MtqGame;
use App\Models\MtqTile;
use App\Services\AbstractServiceComponent;

class GameService extends AbstractServiceComponent
{
    private const DIRECTIONS = [[-1, -1], [0, -1], [1, -1], [1, 0], [1, 1], [0, 1], [-1, 1], [-1, 0]];
    private array $testedTiles = [];
    private array $availableTiles = [];

    public function getGame(): MtqGame
    {
        return $this->gameRepository->getGame();
    }

    public function revealAll()
    {
        $map = $this->mapService->getMap();
        foreach ($map->getTiles() as $tile) {
            if ($tile->getHasTrap()) {
                $this->sendEvent($tile, 'reveal');
            }
        }
    }

    private function fillAvailableTiles(bool $onlyWithZeroTraps = false): void
    {
        if (!empty($this->availableTiles)) {
            return;
        }
        $this->availableTiles = [];
        $tiles = $this->mapService->getMap2Tiles();
        foreach ($tiles as $tile) {
            if ($tile->has_trap || $tile->isRevealed() || $tile->marked_as_clue) {
                continue;
            }
            if ($onlyWithZeroTraps && $tile->traps_around > 0) {
                continue;
            }
            $this->availableTiles[] = $tile->id;
        }
    }

    private function isTileAvailable(MtqTile $tile): bool
    {
        $this->fillAvailableTiles();
        $ret = in_array($tile->id, $this->availableTiles);
        return $ret;
    }

    public function showClue(): bool
    {
        $this->fillAvailableTiles(true);
        if (empty($this->availableTiles)) {
            return false;
        }
        $randomAvailableTileId = $this->availableTiles[array_rand($this->availableTiles)];
        $this->tileService->markTileWithClue($randomAvailableTileId);
        return true;
    }

    public function revealTile(MtqTile $tile) {
        if ($tile->traps_around > 0) {
            $this->sendEvent($tile, 'reveal');
            return;
        }
        $this->_revealTile($tile);
    }

    private function _revealTile(MtqTile $tile)
    {
        if ($this->isTileTested($tile)) {
            return;
        }
        $this->setTileAsTested($tile);
        $this->fillAvailableTiles();
        foreach (self::DIRECTIONS as $dir) {
            $newX = $tile->x + $dir[0];
            $newY = $tile->y + $dir[1];
            if ($this->mapService->isValid($newX, $newY)) {
                $newTile = $this->mapService->getTile($newX, $newY);
                if ($this->isTileAvailable($newTile)) {
                    if ($this->isTileTested($newTile)) {
                        continue;
                    }
                    if ($newTile->traps_around > 0) {
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

    private function isTileTested(MtqTile $tile): bool
    {
        return array_key_exists($tile->id, $this->testedTiles);
    }

    private function setTileAsTested(MtqTile $tile): void
    {
        $this->testedTiles[$tile->id] = true;
    }

}
