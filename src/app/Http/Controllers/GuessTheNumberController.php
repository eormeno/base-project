<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequestFilter;
use App\Services\GuessTheNumber\GuessTheNumberServiceManager;

class GuessTheNumberController extends BaseController
{
    public function __construct(
        GuessTheNumberServiceManager $serviceManager
    ) {
        parent::__construct($serviceManager);
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->serviceManager->get('gameService')->getGame(); // phpcs:ignore
        $this->serviceManager->stateManager->enqueueForRendering($game);
        $this->serviceManager->stateManager->enqueueEvent($request->eventInfo());
        return $this->serviceManager->stateManager->getAllStatesViews();
    }

    public function reset(): void
    {
        $this->serviceManager->get('gameRepository')->reset(); // phpcs:ignore
    }
}
