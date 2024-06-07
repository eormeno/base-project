<?php

namespace App\FSM;

use App\Traits\ToastTrigger;
use App\Utils\CaseConverters;
use App\Utils\ReflectionUtils;

abstract class StateAbstractImpl implements StateInterface
{
    use ToastTrigger;

    protected StateContextInterface $context;
    private static string $_dashed_name;
    public bool $need_restoring = false;

    public static function dashCaseName(): string
    {
        $class = get_called_class();
        $class = substr($class, strrpos($class, '\\') + 1);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $class));
    }

    public function isNeedRestoring(): bool
    {
        return $this->need_restoring;
    }

    public function setNeedRestoring(bool $value): void
    {
        $this->need_restoring = $value;
    }

    public function setContext(StateContextInterface $content)
    {
        $this->context = $content;
    }

    public function onEnter(bool $restoring): void
    {
    }

    public function onExit(): void
    {
    }

    public function passTo(): void
    {
    }

    public function handleRequest(?string $event = null, $data = null) {
        if ($event === null) {
            $this->passTo();
            return;
        }
        $method = 'on' . CaseConverters::snakeToCamel($event) . 'Event';
        if (method_exists($this, $method)) {
            ReflectionUtils::invokeMethod($this, $method, $data);
        } else {
            throw new \Exception("Invalid event: $event");
        }
    }

    public function view()
    {
        $view_name = self::dashCaseName();
        $view_attr = $this->toArray();
        // add an html paragraph element to the view
        $view_attr['p'] = "<p>Guess a number between 1 and 100.</p>";
        $view = view("guess-the-number.$view_name", $view_attr);
        return $view;
    }

    public function toArray(): array
    {
        $properties = get_object_vars($this);
        $array = [];
        foreach ($properties as $key => $value) {
            if ($key[0] != '_') {
                $array[$key] = $value;
            }
        }
        return $array;
    }

}
