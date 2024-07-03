<?php

namespace App\Services;

use App\FSM\IEventListener;
use App\FSM\StateChangedEvent;
use App\FSM\IStateModel;

class EventManager
{
    private array $arrListeners = [];

    public function add(IEventListener $listener)
    {
        $strListenerClass = get_class($listener);
        if (!array_key_exists($strListenerClass, $this->arrListeners)) {
            $this->arrListeners[$strListenerClass] = $listener;
        }
    }

    public function remove(IEventListener $listener)
    {
        $strListenerClass = get_class($listener);
        if (array_key_exists($strListenerClass, $this->arrListeners)) {
            unset($this->arrListeners[$strListenerClass]);
        }
    }

    public function notify(
        IStateModel $model,
        string|null $oldState,
        string|null $newState
    ) {
        $event = new StateChangedEvent($model, $oldState, $newState);
        foreach ($this->arrListeners as $listener) {
            $listener->onEvent($event);
        }
    }
}
