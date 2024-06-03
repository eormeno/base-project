<?php

namespace App\Services\GuessTheNumber;

use App\FSM\StateStorageInterface;
use App\States\GuessTheNumber\Initial;
use App\Services\AbstractServiceComponent;

class GameStateStorageService extends AbstractServiceComponent implements StateStorageInterface
{
    public function getInitialStateClass(): string
    {
        return Initial::class;
    }

    public function readState(): string|null
    {
        $dashed_state_name = $this->gameService->getGame()->state;
        $state_class_name = $this->stateNameToClass($dashed_state_name);
        return $state_class_name;
    }

    public function saveState(string|null $state): void
    {
        $game = $this->gameService->getGame();
        $game->state = $state;
        $game->save();
    }

    private function stateNameToClass(string $dashed_state_name): string
    {
        $namespace = substr($this->getInitialStateClass(), 0, strrpos($this->getInitialStateClass(), '\\'));
        $pascal_state_name = str_replace(' ', '', ucwords(str_replace('-', ' ', $dashed_state_name)));
        return $namespace . '\\' . $pascal_state_name;
    }
}
