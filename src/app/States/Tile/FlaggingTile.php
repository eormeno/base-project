<?php

namespace App\States\Tile;

class FlaggingTile extends Hidden
{
    public function onTileFlagEvent()
    {
        $this->cast()->setHasFlag(true);
        return FlaggedTile::StateClass();
    }

    public function onCancelTileFlaggingEvent()
    {
        return Hidden::StateClass();
    }
}
