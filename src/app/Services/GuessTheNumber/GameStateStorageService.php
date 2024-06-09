<?php

namespace App\Services\GuessTheNumber;

use App\Utils\CaseConverters;
use ReflectionClass;
use App\FSM\StateStorageInterface;
use App\States\GuessTheNumber\Initial;
use App\Services\AbstractServiceComponent;

class GameStateStorageService extends AbstractServiceComponent implements StateStorageInterface
{
    public function getInitialStateClass(): ReflectionClass
    {
        return Initial::StateClass();
    }

    public function readState(): ReflectionClass|null
    {
        $kebab_state_name = $this->gameService->getGame()->state;
        $state_class_name = $this->stateNameToClass($kebab_state_name);
        return $state_class_name;
    }

    public function saveState(string|null $state): void
    {
        $game = $this->gameService->getGame();
        $game->state = $state;
        $game->save();
    }

    private function stateNameToClass(string|null $dashed_state_name): ReflectionClass
    {
        if (!$dashed_state_name) {
            return $this->getInitialStateClass();
        }
        // todo: refactor this to a more generic method. because this class construction assumes
        // that the state class is in the same namespace as the initial state class.
        $namespace = $this->getInitialStateClass()->getNamespaceName();
        $short_class_name = CaseConverters::kebabToPascal($dashed_state_name);
        $full_class_name = $namespace . '\\' . $short_class_name;
        return $full_class_name::StateClass();
    }
}
