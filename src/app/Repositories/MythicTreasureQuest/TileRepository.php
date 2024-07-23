<?php

namespace App\Repositories\MythicTreasureQuest;

use App\Models\MtqTile;
use App\Services\AbstractServiceComponent;

class TileRepository extends AbstractServiceComponent
{
    public function getTileById(int $id): MtqTile
    {
        return $this->mapService->getMap2()->tiles()->find($id);
    }

    public function markTileWithClue(int $tileId): void
    {
        $tile = $this->getTileById($tileId);
        $tile->marked_as_clue = true;
        $tile->save();
        $this->sendEvent($tile, 'clue_marked');
        $this->requireRefresh($tile);
    }

    public function markTileWithFlag(int $tileId): void
    {
        $tile = $this->getTileById($tileId);
        $tile->has_flag = true;
        $tile->save();
        $this->requireRefresh($tile);
    }
}
