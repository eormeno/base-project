<?php

namespace App\Http\Controllers;

use App\FSM\StatesChangeEventListeners;
use App\Services\StateManager;
use App\Http\Requests\EventRequestFilter;
use App\Services\MythicTreasureQuest\MythicTreasureQuestServiceManager;

class MythicTreasureQuestController extends BaseController
{
    public function __construct(
        protected StateManager $stateManager,
        protected MythicTreasureQuestServiceManager $serviceManager
    ) {
        //todo: try to remove this
        StatesChangeEventListeners::clear();
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        $this->stateManager->enqueueForRendering($this->serviceManager, $game, 'main');
        return $this->stateManager->getAllStatesViews($request->eventInfo(), $this->name());
    }

    public function reset(): void
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        $this->stateManager->enqueueForRendering($this->serviceManager, $game, 'main');
        $this->stateManager->reset();
    }
}
