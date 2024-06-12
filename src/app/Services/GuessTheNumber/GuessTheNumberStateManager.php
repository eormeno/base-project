<?php

namespace App\Services\GuessTheNumber;

use App\Services\AbstractStateManager;
use App\Models\GuessTheNumberGame;
use App\States\GuessTheNumber\Initial;

class GuessTheNumberStateManager extends AbstractStateManager
{
    public function __construct()
    {
        $this->serviceManager = new GuessTheNumberGameServiceManager();
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
