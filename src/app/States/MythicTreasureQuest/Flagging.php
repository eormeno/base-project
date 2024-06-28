<?php

namespace App\States\MythicTreasureQuest;

class Flagging extends Playing
{
    public function onCancelFlaggingEvent()
    {
        return Playing::StateClass();
    }
}
