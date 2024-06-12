<?php

namespace App\Http\Controllers;

use App\Services\MythicTreasureQuest\MythicTreasureQuestStateManager;
use App\Http\Requests\EventRequestFilter;

class MythicTreasureQuestController extends BaseController
{
    public function __construct(protected MythicTreasureQuestStateManager $stateManager
    ) {
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->stateManager->service('gameService')->getGame(); // phpcs:ignore
        return $this->stateManager->getState($game, $request->eventInfo());
    }

    public function reset() : void
    {
        $this->stateManager->reset($this->stateManager->service('gameService')->getGame()); // phpcs:ignore
    }
}
