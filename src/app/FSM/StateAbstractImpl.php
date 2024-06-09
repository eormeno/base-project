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

    public function onReload(): void
    {
    }

    public function onSave(): void
    {
    }

    public function onEnter(): void
    {
    }

    public function onRefresh(): void
    {
    }

    public function onExit(): void
    {
    }

    public function passTo()
    {
        return self::StateClass();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event === null) {
            $cls = $this->passTo();
            if ($cls == self::StateClass()) {
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
        $view_name = CaseConverters::pascalToKebab(self::StateClass()->getShortName());
        //todo: check if view exists before returning and resolve the view name
        $view = view("guess-the-number.$view_name", $this->toArray());
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
