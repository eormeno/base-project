<?php

namespace App\Http\Controllers;

use App\Services\StateManager;
use App\Services\AbstractServiceManager;
use App\Http\Requests\EventRequestFilter;
use App\Services\GuessTheNumber\GuessTheNumberGameServiceManager;

class GuessTheNumberController extends BaseController
{
    private AbstractServiceManager $serviceManager;
    private StateManager $stateManager;

    public function __construct(GuessTheNumberGameServiceManager $serviceManager
    ) {
        $this->serviceManager = $serviceManager;
        $this->stateManager = new StateManager($serviceManager);
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->serviceManager->gameService->getGame();
        return $this->stateManager->getState($game, $request->eventInfo());
    }

    public function reset() : void
    {
        $this->stateManager->reset($this->serviceManager->gameService->getGame());
    }
}
