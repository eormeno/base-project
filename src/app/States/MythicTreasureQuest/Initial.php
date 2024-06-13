<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;

class Initial extends StateAbstractImpl
{
    public function onStartQuestEvent()
    {
        return Playing::StateClass();
    }
}
