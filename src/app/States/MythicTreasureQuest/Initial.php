<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\AState;

class Initial extends AState
{
    public function onStartQuestEvent()
    {
        return Playing::StateClass();
    }
}
