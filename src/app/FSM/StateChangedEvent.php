<?php

namespace App\FSM;

class StateChangedEvent
{
    private IStateManagedModel $model;
    private string|null $oldState;
    private string|null $newState;

    public function __construct(
        IStateManagedModel $model,
        string|null $oldState,
        string|null $newState
    ) {
        $this->model = $model;
        $this->oldState = $oldState;
        $this->newState = $newState;
    }

    public function getModel(): IStateManagedModel
    {
        return $this->model;
    }

    public function getOldState(): string|null
    {
        return $this->oldState;
    }

    public function getNewState(): string|null
    {
        return $this->newState;
    }
}