<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\Http\Requests\EventRequestFilter;
use App\Http\Controllers\StateContextController;
use App\Services\GuessTheNumber\GuessTheNumberGameServiceManager;
use App\Services\StateManager;

class GuessTheNumberController extends StateContextController
{
    public function __construct(
        GuessTheNumberGameServiceManager $serviceManager,
        protected StateManager $stateManager
    ) {
        parent::__construct($serviceManager);
        $this->stateStorage = $serviceManager->gameStateStorageService;
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->serviceManager->gameService->getGame();
        $this->stateManager->getState($game);

        return $this->request($request->eventInfo())->view();
    }
}
