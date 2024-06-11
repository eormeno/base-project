<?php

namespace App\Services;

use App\Helpers\StateUpdateHelper;
use ReflectionClass;
use App\FSM\StateInterface;
use App\Helpers\StatesLocalCache;
use App\FSM\StateContextInterface;
use Illuminate\Database\Eloquent\Model;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;

class StateContextImpl extends AbstractServiceComponent implements StateContextInterface
{
    protected ?StateInterface $__state = null;
    protected AbstractServiceManager $serviceManager;
    protected StateUpdateHelper $stateStorage;

    public function __construct(AbstractServiceManager $serviceManager, Model $object, array $stateConfig)
    {
        $this->serviceManager = $serviceManager;
        $this->stateStorage = new StateUpdateHelper($object, $stateConfig);
    }

    private function setState(ReflectionClass $reflection_state_class): void
    {
        $new_instance = StatesLocalCache::getStateInstance($reflection_state_class);
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
        return $this->serviceManager->$attributeName;
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
        $sta_registered = StatesLocalCache::findRegisteredStateInstance($reflection_state_class);
        $this->stateStorage->saveState($reflection_state_class);
        $this->setState($sta_registered::StateClass());
    }

    public function reset(): void
    {
        $this->stateStorage->saveState(null);
    }

}