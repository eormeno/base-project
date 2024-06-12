<?php

namespace App\Services\MythicTreasureQuest;

use App\Models\GuessTheNumberGame;
use App\Services\AbstractStateManager;
use App\States\GuessTheNumber\Initial;

class MythicTreasureQuestStateManager extends AbstractStateManager
{
    public function __construct()
    {
        $this->serviceManager = new MythicTreasureQuestServiceManager();
        $this->statesMap = [
            GuessTheNumberGame::class => [
                'initial' => Initial::class,
                'state_field' => 'state',
                'id_field' => 'id',
                'state_context' => null
            ],
        ];
    }
}
