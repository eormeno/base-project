<?php

namespace App\Services\MythicTreasureQuest;

use App\Services\AbstractStateManager;

class MythicTreasureQuestStateManager extends AbstractStateManager
{
    public function __construct()
    {
        $this->serviceManager = new MythicTreasureQuestServiceManager();
    }
}
