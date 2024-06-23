<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequestFilter;
use App\Services\MythicTreasureQuest\MythicTreasureQuestServiceManager;

class MythicTreasureQuestController extends BaseController
{
    public function __construct(
        protected MythicTreasureQuestServiceManager $serviceManager
    ) {
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        $this->serviceManager->stateManager->enqueueForRendering($game, 'main');
        $this->serviceManager->stateManager->enqueueEvent($request->eventInfo());
        return $this->serviceManager->stateManager->getAllStatesViews($this->name());
    }

    public function reset(): void
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        $this->serviceManager->stateManager->enqueueForRendering($game, 'main');
        $this->serviceManager->stateManager->reset();
    }
}
