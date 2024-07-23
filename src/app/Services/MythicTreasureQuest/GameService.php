<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\MtqGame;
use App\Models\MythicTreasureQuestGame;
use App\Models\MythicTreasureQuest\Tile;
use App\Services\AbstractServiceComponent;
use App\Traits\DebugHelper;

class GameService extends AbstractServiceComponent
{
    use DebugHelper;
    private const DIRECTIONS = [[-1, -1], [0, -1], [1, -1], [1, 0], [1, 1], [0, 1], [-1, 1], [-1, 0]];
    private array $testedTiles = [];
    private array $availableTiles = [];


    public function getGame(): MythicTreasureQuestGame
    {
        return $this->gameRepository->getGame();
    }

    public function getGame2(): MtqGame
    {
        return $this->gameRepository->getGame2();
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

    private function fillAvailableTiles()
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
            $this->availableTiles[] = $tile;
        }
    }

    private function isTileAvailable(Tile $tile): bool
    {
        $this->fillAvailableTiles();
        $ret = in_array($tile->getId(), $this->availableTiles);
        return $ret;
    }

    public function showClue(): bool
    {
        $this->fillAvailableTiles();
        if (empty($this->availableTiles)) {
            return false;
        }
        $randomAvailableTile = $this->availableTiles[array_rand($this->availableTiles)];

        //$tile = $this->tileRepository->getTileById($tileId);
        $this->tileRepository->markTileWithClue($randomAvailableTile);
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
        //$map = $tile->getMap();
        $map = $this->mapService->getMap();
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
