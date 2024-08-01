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
        return $this->stateManager->statesViews($game, $request->eventInfo());
    }

    public function reset(): void
    {
        $this->serviceManager->get('gameRepository')->reset(); // phpcs:ignore
        $this->stateManager->reset();
        session()->forget('events');
    }
}
