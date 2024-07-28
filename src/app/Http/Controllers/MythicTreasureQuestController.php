<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequestFilter;
use App\Services\MythicTreasureQuest\MythicTreasureQuestServiceManager;
use App\Services\StateManager;

class MythicTreasureQuestController extends BaseController
{
    private StateManager $stateManager;
    public function __construct(
        MythicTreasureQuestServiceManager $serviceManager
    ) {
        parent::__construct($serviceManager);
        $this->stateManager = $serviceManager->stateManager;
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        // $this->stateManager->setRootModel($game);
        // $this->stateManager->enqueueForRendering($game);
        $this->stateManager->enqueueEvent($request->eventInfo());
        return $this->stateManager->getAllStatesViews2($game);
    }

    public function reset(): void
    {
        $this->serviceManager->get('gameRepository')->reset(); // phpcs:ignore
        $this->stateManager->reset();
    }
}
