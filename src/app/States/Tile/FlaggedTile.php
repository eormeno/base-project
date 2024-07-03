<?php

namespace App\States\Tile;

class FlaggedTile extends Hidden
{
    public function onTileFlagEvent()
    {
        $this->cast()->setHasFlag(false);
        return FlaggingTile::StateClass();
    }
}
