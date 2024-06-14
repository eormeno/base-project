<?php

namespace App\Http\Controllers;

use App\Services\StateManager;
use App\Http\Requests\EventRequestFilter;
use App\Services\MythicTreasureQuest\MythicTreasureQuestServiceManager;

class MythicTreasureQuestController extends BaseController
{
    public function __construct(
        protected StateManager $stateManager,
        protected MythicTreasureQuestServiceManager $serviceManager
    ) {
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        return $this->stateManager->getStatesViews(
            $this->serviceManager,
            $game,
            $request->eventInfo(),
            $this->name()
        );
    }

    public function reset(): void
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        $this->stateManager->reset($this->serviceManager, $game);
    }
}
