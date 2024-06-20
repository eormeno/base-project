<?php

namespace App\FSM;

use App\FSM\IEventListener;
use App\FSM\StateChangedEvent;
use App\FSM\IStateManagedModel;

class StatesChangeEventListeners
{
    private const LISTENERS = 'listeners';

    public static function add(IEventListener $listener)
    {
        $strListenerClass = get_class($listener);
        $arrListeners = session(self::LISTENERS, []);
        if (!array_key_exists($strListenerClass, $arrListeners)) {
            $arrListeners[$strListenerClass] = $listener;
            session()->put(self::LISTENERS, $arrListeners);
        }
    }

    public static function remove(IEventListener $listener)
    {
        $strListenerClass = get_class($listener);
        $arrListeners = session(self::LISTENERS, []);
        if (array_key_exists($strListenerClass, $arrListeners)) {
            unset($arrListeners[$strListenerClass]);
            session()->put(self::LISTENERS, $arrListeners);
        }
    }

    public static function notify(
        IStateManagedModel $model,
        string|null $oldState,
        string|null $newState
    ) {
        $arrListeners = session(self::LISTENERS, []);
        $event = new StateChangedEvent($model, $oldState, $newState);
        foreach ($arrListeners as $listener) {
            $listener->onEvent($event);
        }
    }

    public static function clear()
    {
        session()->forget(self::LISTENERS);
    }
}
