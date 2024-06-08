<?php

namespace App\FSM;

use ReflectionClass;
use App\Traits\ToastTrigger;
use App\Utils\CaseConverters;
use App\Utils\ReflectionUtils;

abstract class StateAbstractImpl implements StateInterface
{
    use ToastTrigger;

    protected StateContextInterface $context;
    public bool $need_restoring = false;

    public static function StateClass(): ReflectionClass
    {
        return new ReflectionClass(static::class);
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

    public function onRefresh(): void
    {
    }

    public function onExit(): void
    {
    }

    public function passTo(): ReflectionClass
    {
        return self::StateClass();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event === null) {
            $cls = $this->passTo();
            if ($cls === self::StateClass()) {
                return;
            }
            return $this->context->setState($cls);
        }
        $method = 'on' . CaseConverters::snakeToCamel($event) . 'Event';
        if (method_exists($this, $method)) {
            $ref_cls = ReflectionUtils::invokeMethod($this, $method, $data);
            if ($ref_cls) {
                $this->context->setState($ref_cls);
            }
        } else {
            throw new \Exception("Invalid event: $event");
        }
    }

    public function view()
    {
        //$view_name = self::dashCaseName();
        $view_name = CaseConverters::pascalToKebab(self::StateClass()->getShortName());
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
