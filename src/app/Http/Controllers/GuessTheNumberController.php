<?php
namespace App\Http\Controllers;

use App\Http\Requests\EventRequestFilter;
use App\Services\GuessTheNumber\GuessTheNumberStateManager;

class GuessTheNumberController extends BaseController
{
    private $gameService;

    public function __construct(
        protected GuessTheNumberStateManager $stateManager
    ) {
        $this->gameService = $this->stateManager->service('gameService');
    }

    public function event(EventRequestFilter $request)
    {
        $game = $this->gameService->getGame(); // phpcs:ignore
        return $this->stateManager->getState($game, $request->eventInfo(), $this->name());
    }

    public function reset(): void
    {
        $game = $this->gameService->getGame(); // phpcs:ignore
        $this->stateManager->reset($game);
    }
}
