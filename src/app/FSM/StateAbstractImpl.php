<?php

namespace App\FSM;

use ReflectionClass;
use App\Traits\DebugHelper;
use App\Traits\ToastTrigger;
use App\Utils\CaseConverters;
use App\Utils\ReflectionUtils;

abstract class StateAbstractImpl implements StateInterface
{
    use ToastTrigger;
    use DebugHelper;

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

    #region Callbacks
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
    #endregion

    public function handleRequest(array $eventInfo): ReflectionClass
    {
        $event = $eventInfo['event'];
        $data = $eventInfo['data'];
        $source = $eventInfo['source'];
        if ($event === null) {
            $rfl_class = $this->passTo();
            if ($rfl_class == self::StateClass()) {
                return self::StateClass();
            }
            return $rfl_class;
        }
        if ($source) {
            $namespace = get_class($this);
            $method = 'on' . CaseConverters::snakeToPascal($event) . 'Event';
            //$this->log("Firing $namespace->$method from $source.");
        }
        if (method_exists($this, $method)) {
            // if ($source) {
            //     $this->log("Event $event source is $source in $namespace.");
            // }
            $ref_cls = ReflectionUtils::invokeMethod($this, $method, $data);
            if ($ref_cls) {
                return $ref_cls;
            }
        }
        return $this->passTo();
    }

    public function view(string $controller_name)
    {
        $view_name = CaseConverters::pascalToKebab(self::StateClass()->getShortName());
        //todo: check if view exists before returning and resolve the view name
        $view = view("$controller_name.$view_name", $this->publicPropertiesToArray());
        return $view;
    }

    private function publicPropertiesToArray(): array
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
