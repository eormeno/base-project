<?php

namespace App\States\MythicTreasureQuest;

class Flagging extends Playing
{
    public function onCancelFlaggingEvent()
    {
        $this->sendSignal('cancelTileFlagging');
        return Playing::StateClass();
    }
}
