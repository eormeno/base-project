<?php

namespace App\FSM;

use App\Traits\ToastTrigger;

abstract class StateAbstractImpl implements StateInterface
{
    use ToastTrigger;

    protected StateContextInterface $context;
    private static string $_dashed_name;

    public static function dashCaseName(): string
    {
        $class = get_called_class();
        $class = substr($class, strrpos($class, '\\') + 1);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $class));
    }

    public function setContext(StateContextInterface $content)
    {
        $this->context = $content;
    }

    public function onEnter(): void
    {
    }

    public function onExit(): void
    {
    }

    abstract public function handleRequest(?string $event = null, $data = null);

}
