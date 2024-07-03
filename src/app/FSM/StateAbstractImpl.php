<?php

namespace App\FSM;

use App\Traits\DebugHelper;
use ReflectionClass;
use App\Traits\ToastTrigger;
use App\Utils\CaseConverters;
use App\Utils\ReflectionUtils;

abstract class StateAbstractImpl implements IState
{
    use ToastTrigger;
    use DebugHelper;

    protected IStateContext $context;
    public IStateModel $model;
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

    public function setManagedModel(IStateModel $model)
    {
        $this->model = $model;
    }

    public function setContext(IStateContext $content)
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
        $destination = $eventInfo['destination'];
        if ($event === null) {
            $rfl_class = $this->passTo();
            if ($rfl_class == self::StateClass()) {
                return self::StateClass();
            }
            return $rfl_class;
        }
        if ($destination != 'all') {
            if ($source != $destination) {
                return $this->passTo();
            }
        }
        $method = 'on' . CaseConverters::snakeToPascal($event) . 'Event';
        if (method_exists($this, $method)) {
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

    protected function requireRefresh(IStateModel|string|null $modelOrAlias = null): void
    {
        $strAlias = '';
        if (is_string($modelOrAlias)) {
            $strAlias = $modelOrAlias;
        } else {
            $modelOrAlias = $modelOrAlias ?? $this->model;
            $strAlias = $modelOrAlias->getAlias();
        }
        $this->context->stateManager->requireRefresh($strAlias);
    }

    protected function sendSignal(string $event, array $data = [])
    {
        $this->context->stateManager->enqueueEvent([
            'event' => $event,
            'is_signal' => true,
            'source' => $this->model->getAlias(),
            'data' => $data,
            'destination' => 'all'
        ]);
    }

    protected function doAction(string $action_name, string $method, $parameter = null): void
    {
        $action_name = CaseConverters::snakeToPascal($action_name);
        $action_class = 'App\\Actions\\' . $this->context->serviceManager->baseName() . '\\' . $action_name . 'Action';
        $class = new ReflectionClass($action_class);
        $instance = $class->newInstance($this->context->serviceManager);
        $instance->$method($parameter);
    }

}
