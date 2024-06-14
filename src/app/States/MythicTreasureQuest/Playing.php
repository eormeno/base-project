<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;

class Playing extends StateAbstractImpl
{
    public function onTileClickEvent(int $x, int $y): void
    {
        $this->warningToast('You clicked on tile (' . $x . ', ' . $y . ')');
    }
}
