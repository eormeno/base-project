<?php

namespace App\Http\Controllers\GTNStates;

use App\FSM\FSMContext;
use App\FSM\FSMState;

abstract class GTNState implements FSMState
{

    /**
     * Returns the name of the class in snake_case
     */
    public function name()
    {
        $class = get_class($this);
        $class = substr($class, strrpos($class, '\\') + 1);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class));
    }

    abstract public function handleRequest(FSMContext $context, $event = null, $data = null);

    /**
     * returns an instance given the class name in snake_case
     * @param $name
     * @return GTNState
     */
    public static function fromName($name)
    {
        $class = 'App\Http\Controllers\GTNStates\\' . str_replace('_', '', ucwords($name, '_'));
        return new $class;
    }
}
