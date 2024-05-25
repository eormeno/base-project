<?php

namespace App\FSM;

use App\Traits\ToastTrigger;

abstract class StateAbstractImpl implements StateInterface
{
    use ToastTrigger;

    /**
     * Returns the name of the class in dash-case
     */
    public function name(): string
    {
        $class = get_class($this);
        $class = substr($class, strrpos($class, '\\') + 1);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $class));
    }

    public static function getName(): string
    {
        return (new static)->name();
    }

    abstract public function handleRequest(StateContextInterface $context, $event = null, $data = null);

    /**
     * returns an instance given the class name in dash-case
     * @param $name
     * @return StateAbstractImpl
     */
    public static function fromName($namespace, $name): StateAbstractImpl
    {
        $class = $namespace . str_replace('-', '', ucwords($name, '-'));
        return new $class;
    }
}
