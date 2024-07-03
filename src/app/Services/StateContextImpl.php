<?php

namespace App\Services;

use App\Traits\DebugHelper;
use ReflectionClass;
use App\Utils\Constants;
use App\FSM\IState;
use App\FSM\IStateModel;
use App\Helpers\StatesLocalCache;
use App\FSM\IStateContext;
use App\Helpers\StateUpdateHelper;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;

class StateContextImpl extends AbstractServiceComponent implements IStateContext
{
    use DebugHelper;
    protected ?IState $__state = null;
    protected AbstractServiceManager $serviceManager;
    protected StateUpdateHelper $stateUpdater;
    protected StateManager $stateManager;
    protected IStateModel $object;
    protected int $id;
    public bool $isStateChanged = false;

    public function __construct(
        AbstractServiceManager $serviceManager,
        IStateModel $object
    ) {
        $this->serviceManager = $serviceManager;
        $this->stateManager = $serviceManager->stateManager;
        $this->object = $object;
        $this->id = $object->getId();
        $this->stateUpdater = new StateUpdateHelper($serviceManager, $object);
    }

    private function setState(ReflectionClass $reflection_state_class): void
    {
        $new_instance = StatesLocalCache::getStateInstance($reflection_state_class, $this->id);
        $new_instance->setContext($this);
        $new_instance->setManagedModel($this->object);
        if ($new_instance->isNeedRestoring()) {
            $new_instance->setNeedRestoring(false);
            $new_instance->reset();
            $new_instance->onReload();
        }
        // TODO: OJO CAMBIO ACÁ
        if ($this->__state && $this->__state != $new_instance) {
            $this->__state->onExit();
            $new_instance->reset();
            $new_instance->onEnter();
        } else {
            if (!$this->__state) {
                $new_instance->reset();
                $new_instance->onEnter();
            }
        }
        $this->__state = $new_instance;
        $this->__state->onRefresh();
    }

    public function __get($attributeName)
    {
        if (property_exists($this, $attributeName)) {
            return $this->$attributeName;
        }
        return $this->serviceManager->get($attributeName);
    }

    public function request(array $eventInfo): IState
    {
        $this->restoreState();
        $initial_state = $this->__state;
        do {
            $this->restoreState();
            $current_state = $this->__state;
            $this->setState($current_state->handleRequest($eventInfo));
            $changed_state = $this->__state;
            if ($changed_state != $current_state) {
                $this->stateUpdater->saveState($changed_state::StateClass());
            }
            $eventInfo = Constants::EMPTY_EVENT;
        } while ($current_state != $changed_state);
        $this->isStateChanged = $initial_state != $changed_state;
        return $changed_state;
    }

    protected function restoreState(): void
    {
        $rflState = $this->stateUpdater->readState();
        $staRegistered = StatesLocalCache::findRegisteredStateInstance($rflState, $this->id);
        $this->stateUpdater->saveState($rflState);
        $this->setState($staRegistered::StateClass());
    }
}
