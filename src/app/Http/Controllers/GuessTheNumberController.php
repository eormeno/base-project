<?php
namespace App\Http\Controllers;

use App\Http\Requests\EventRequestFilter;
use App\Services\GuessTheNumber\GuessTheNumberStateManager;

class GuessTheNumberController extends BaseController
{
    public function __construct(
        protected GuessTheNumberStateManager $stateManager
    ) {
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->stateManager->service('gameService')->getGame(); // phpcs:ignore
        return $this->stateManager->getState($game, $request->eventInfo(), $this->name());
    }

    public function reset(): void
    {
        $this->stateManager->reset($this->stateManager->service('gameService')->getGame()); // phpcs:ignore
    }
}
