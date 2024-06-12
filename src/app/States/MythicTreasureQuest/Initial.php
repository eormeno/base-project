<?php

namespace App\States\MythicTreasureQuest;

use App\FSM\StateAbstractImpl;

class Initial extends StateAbstractImpl
{
    public function onStartQuestEvent()
    {
        $this->warningToast("You have already started the quest.");
    }
}
