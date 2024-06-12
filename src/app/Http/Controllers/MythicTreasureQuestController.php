<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequestFilter;
use App\Services\MythicTreasureQuest\MythicTreasureQuestStateManager;

class MythicTreasureQuestController extends BaseController
{
    public function __construct(
        protected MythicTreasureQuestStateManager $stateManager
    ) {
    }

    public function event(EventRequestFilter $request)
    {
        //$game = $this->stateManager->service('gameService')->getGame(); // phpcs:ignore
        //return $this->stateManager->getState($game, $request->eventInfo());
        return '<h1>Mythic Treasure Quest</h1>';
    }

    public function reset(): void
    {
        $this->stateManager->reset($this->stateManager->service('gameService')->getGame()); // phpcs:ignore
    }
}
