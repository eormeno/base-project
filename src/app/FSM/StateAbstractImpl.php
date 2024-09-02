<?php

namespace App\FSM;

use Exception;
use ReflectionClass;
use App\Traits\DebugHelper;
use App\Traits\ToastTrigger;
use App\Utils\CaseConverters;
use App\Utils\ReflectionUtils;
use Illuminate\Support\Carbon;

abstract class StateAbstractImpl implements IState
{
    use ToastTrigger;
    use DebugHelper;

    protected IStateContext $context;
    //protected array $arrStrChildrenVID = [];
    private IStateModel $_model;
    public Carbon|null $enteredAt = null;

    public static function StateClass(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }

    public function setStateModel(IStateModel $model)
    {
        // search the 'model' property in the class
        $properties = get_object_vars($this);
        if (array_key_exists('model', $properties)) {
            $this->model = $model;
        }
        $this->_model = $model;
        $this->restoreChildren();
    }

    private function restoreChildren(): void
    {
        $children = $this->_model->children; // phpcs:ignore
        $modelName = $this->_model->getAlias();
        if ($children) {
            foreach ($children as $viewId => $strAlias) {
                if (property_exists($this, $viewId)) {
                    $this->$viewId = $strAlias;
                    $this->log("Restored $modelName->$$viewId");
                }
            }
        }
    }

    public function getStateModel(): IStateModel
    {
        return $this->_model;
    }

    public function defineSubState(IStateModel $subState, &$localVariable): void
    {
        $localVariableName = ReflectionUtils::getVariableName($localVariable);
        $this->log("Defining substate $localVariableName");
    }

    public function defineSubstates(): void
    {
    }

    public function addChild(IStateModel $child, string $viewId): string
    {
        if (!($child instanceof IStateModel)) {
            throw new Exception('Model must be an instance of IStateModel');
        }
        $strAlias = $child->getAlias();
        $children = $this->_model->children; // phpcs:ignore
        if (!$children || !array_key_exists($viewId, $children)) {
            $children[$viewId] = $strAlias;
        }
        if (is_string($children[$viewId])) {
            if ($children[$viewId] !== $strAlias) {
                $children[$viewId] = [$children[$viewId]];
                $children[$viewId][] = $strAlias;
            }
        }
        if (is_array($children[$viewId])) {
            if (!in_array($strAlias, $children[$viewId])) {
                $children[$viewId][] = $strAlias;
            }
        }
        $this->_model->children = $children;
        $this->_model->save();  // phpcs:ignore
        return $strAlias;
    }

    public function removeChild(string $strAlias): void
    {
        $children = $this->_model->children; // phpcs:ignore
        if (!$children) {
            return;
        }
        $key = array_search($strAlias, $children);
        if ($key !== false) {
            unset($children[$key]);
            $this->_model->children = $children;
            $this->_model->save();  // phpcs:ignore
        }
    }

    public function addChilren(array $models, string $viewId): array
    {
        $arrStrChildrenVID = [];
        foreach ($models as $model) {
            $arrStrChildrenVID[] = $this->addChild($model, $viewId);
        }
        return $arrStrChildrenVID;
    }

    public function getChildren(): array
    {
        $children = $this->_model->children; // phpcs:ignore
        if (!$children) {
            return [];
        }
        $ret = [];
        foreach ($children as $viewId => $strAlias) {
            if (is_array($strAlias)) {
                foreach ($strAlias as $alias) {
                    $ret[] = $alias;
                }
            } else {
                $ret[] = $strAlias;
            }
        }
        return $ret;
    }

    public function setContext(IStateContext $content)
    {
        $this->context = $content;
    }

    public function reset(): void
    {
        $this->_model->children = [];
        $this->_model->save(); // phpcs:ignore
    }

    #region Callbacks
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
        // TODO: REVIEW THIS PART
        if ($destination != 'all') {
            if ($source != $destination) {
                if ($event === 'refresh') {
                    return $this->passTo();
                }
                // $this->log(">>> Event '$event' from $source to $destination");
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
        $exclude = ['context', 'arrStrChildrenVID'];
        $properties = get_object_vars($this);
        $array = [];
        foreach ($properties as $key => $value) {
            if (in_array($key, $exclude)) {
                continue;
            }
            // exclude private and protected properties
            if (strpos($key, "\0") === false) {
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
            $modelOrAlias = $modelOrAlias ?? $this->_model;
            $strAlias = $modelOrAlias->getAlias();
        }
        $this->context->stateManager->requireRefresh($strAlias);
    }

    protected function sendEventTo(string $event, IStateModel|string $destinationModelOrAlias, array $data = [])
    {
        $strDestinationAlias = '';
        if (is_string($destinationModelOrAlias)) {
            $strDestinationAlias = $destinationModelOrAlias;
        } else {
            $strDestinationAlias = $destinationModelOrAlias->getAlias();
        }
        $this->context->stateManager->enqueueEvent([
            'event' => $event,
            'is_signal' => false,
            'source' => $this->_model->getAlias(),
            'data' => $data,
            'destination' => $strDestinationAlias
        ]);
    }

    protected function sendSignal(string $event, array $data = [])
    {
        $this->context->stateManager->enqueueEvent([
            'event' => $event,
            'is_signal' => true,
            'source' => $this->_model->getAlias(),
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
