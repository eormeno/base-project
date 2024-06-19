<?php

namespace App\Services;

use ReflectionClass;
use App\FSM\StateInterface;
use App\FSM\IStateManagedModel;
use App\Helpers\StatesLocalCache;
use App\FSM\StateContextInterface;
use App\Helpers\StateUpdateHelper;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;

class StateContextImpl extends AbstractServiceComponent implements StateContextInterface
{
    protected ?StateInterface $__state = null;
    protected AbstractServiceManager $serviceManager;
    protected StateUpdateHelper $stateStorage;
    protected StateManager $stateManager;
    protected int $id;

    public function __construct(
        StateManager $stateManager,
        AbstractServiceManager $serviceManager,
        IStateManagedModel $object
    ) {
        $this->stateManager = $stateManager;
        $this->serviceManager = $serviceManager;
        $this->id = $object->getId();
        $this->stateStorage = new StateUpdateHelper($object);
    }

    private function setState(ReflectionClass $reflection_state_class): void
    {
        $new_instance = StatesLocalCache::getStateInstance($reflection_state_class, $this->id);
        $new_instance->setContext($this);
        if ($new_instance->isNeedRestoring()) {
            $new_instance->setNeedRestoring(false);
            $new_instance->onReload();
        }
        if ($this->__state && $this->__state != $new_instance) {
            $this->__state->onExit();
            $new_instance->onEnter();
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

    public function request(array $eventInfo): StateInterface
    {
        do {
            $this->restoreState();
            $current_state = $this->__state;
            $this->setState($current_state->handleRequest($eventInfo));
            $changed_state = $this->__state;
            $this->stateStorage->saveState($changed_state::StateClass());
            $eventInfo['event'] = null;
        } while ($current_state != $changed_state);
        return $changed_state;
    }

    protected function restoreState(): void
    {
        $reflection_state_class = $this->stateStorage->readState();
        $sta_registered = StatesLocalCache::findRegisteredStateInstance($reflection_state_class, $this->id);
        $this->stateStorage->saveState($reflection_state_class);
        $this->setState($sta_registered::StateClass());
    }

    public function reset(): void
    {
        StatesLocalCache::reset(); // todo: try to reset speific object's states only
        $this->stateStorage->saveState(null);
    }

}
