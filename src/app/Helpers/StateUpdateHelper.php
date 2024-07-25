<?php

namespace App\Helpers;

use App\Traits\DebugHelper;
use ReflectionClass;
use App\FSM\IStateModel;
use App\Utils\CaseConverters;
use App\Services\EventManager;
use Illuminate\Support\Carbon;
use App\Services\AbstractServiceManager;

class StateUpdateHelper
{
    use DebugHelper;
    protected IStateModel $object;
    protected AbstractServiceManager $serviceManager;
    protected EventManager $eventManager;

    public function __construct(
        AbstractServiceManager $serviceManager,
        IStateModel $object
    ) {
        $this->object = $object;
        $this->serviceManager = $serviceManager;
        $this->eventManager = $serviceManager->eventManager;
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
        // TODO: modify here because when the state is the same as the previous state, the event should not be triggered.
        $oldState = $this->readState();
        $newState = $rfl_state;
        if ($rfl_state) {
            $rfl_state = CaseConverters::pascalToKebab($rfl_state->getShortName());
        }
        $this->object->updateState($rfl_state);
        $this->eventManager->notify($this->object, $oldState->getShortName(), $newState->getShortName());
    }

    public function getEnteredAt(): Carbon|null
    {
        $enteredAt = $this->object->getEnteredAt();
        return $enteredAt ? Carbon::parse($enteredAt) : null;
    }

    public function setEnteredAt(Carbon|string|null $enteredAt): void
    {
        $this->object->setEnteredAt($enteredAt);
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
