<?php

namespace App\Helpers;

use ReflectionClass;
use App\Utils\CaseConverters;
use App\FSM\IStateManagedModel;
use App\FSM\StatesChangeEventListeners;

class StateUpdateHelper
{
    protected IStateManagedModel $object;

    public function __construct(IStateManagedModel $object)
    {
        $this->object = $object;
    }

    public function getInitialStateClass(): ReflectionClass
    {
        return $this->object->getInitialStateClass();
    }

    public function readState(): ReflectionClass|null
    {
        $kebab_state_name = $this->object->getState();
        $rfl_class = $this->stateNameToClass($kebab_state_name);
        return $rfl_class;
    }

    public function saveState(ReflectionClass|null $rfl_state): void
    {
        $oldState = $this->readState();
        $newState = $rfl_state;
        if ($newState == $oldState) {
            return;
        }
        if ($rfl_state) {
            $rfl_state = CaseConverters::pascalToKebab($rfl_state->getShortName());
        }
        $this->object->updateState($rfl_state);
        StatesChangeEventListeners::notify($this->object, $oldState, $newState);
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
