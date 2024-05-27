<?php

namespace App\FSM;

use App\Traits\ToastTrigger;

abstract class StateAbstractImpl implements StateInterface
{
    use ToastTrigger;

    protected StateContextInterface $context;

    /**
     * Returns the name of the class in dash-case
     */
    public static function name(): string
    {
        $class = get_called_class();
        $class = substr($class, strrpos($class, '\\') + 1);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $class));
    }

    public function setContent(StateContextInterface $content)
    {
        $this->context = $content;
    }

    abstract public function handleRequest(?string $event = null, $data = null);

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
