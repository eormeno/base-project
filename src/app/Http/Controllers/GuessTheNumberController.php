<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequestFilter;
use App\Services\GuessTheNumber\GuessTheNumberGameServiceManager;

class GuessTheNumberController extends BaseController
{
    public function __construct(
        protected GuessTheNumberGameServiceManager $serviceManager
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
        $this->serviceManager->get('gameRepository')->reset(); // phpcs:ignore
    }
}
