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
        $this->stateManager->setControllerKebabName($this->name());
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        $this->stateManager->enqueueForRendering($this->serviceManager, $game, 'main');
        $this->stateManager->enqueueEvent($request->eventInfo());
        return $this->stateManager->getAllStatesViews();
    }

    public function reset(): void
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        $this->stateManager->enqueueForRendering($this->serviceManager, $game, 'main');
        $this->stateManager->reset();
    }
}
