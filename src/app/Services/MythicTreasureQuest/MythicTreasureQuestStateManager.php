<?php

namespace App\Services\MythicTreasureQuest;

use App\Services\AbstractStateManager;
use App\Models\MythicTreasureQuestGame;
use App\States\MythicTreasureQuest\Initial;

class MythicTreasureQuestStateManager extends AbstractStateManager
{
    public function __construct()
    {
        $this->serviceManager = new MythicTreasureQuestServiceManager();
        $this->statesMap = [
            MythicTreasureQuestGame::class => [
                'initial' => Initial::class,
                'state_field' => 'state',
                'id_field' => 'id',
                'state_context' => null
            ],
        ];
    }
}
