<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequestFilter;
use App\Services\MythicTreasureQuest\MythicTreasureQuestServiceManager;

class MythicTreasureQuestController extends BaseController
{
    public function __construct(
        MythicTreasureQuestServiceManager $serviceManager
    ) {
        parent::__construct($serviceManager);
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        $this->stateManager->enqueueEvent($request->eventInfo());
        return $this->stateManager->getAllStatesViews2($game);
    }

    public function reset(): void
    {
        $this->serviceManager->get('gameRepository')->reset(); // phpcs:ignore
        $this->stateManager->reset();
        session()->forget('events');
    }
}
