<?php

namespace App\Services;

use ReflectionClass;
use App\Utils\Constants;
use App\FSM\StateInterface;
use App\Traits\DebugHelper;
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
    protected StateUpdateHelper $stateUpdater;
    protected StateManager $stateManager;
    protected int $id;
    protected string $objectType;
    protected string $shortObjectType;

    use DebugHelper;

    public function __construct(
        StateManager $stateManager,
        AbstractServiceManager $serviceManager,
        IStateManagedModel $object
    ) {
        $this->stateManager = $stateManager;
        $this->serviceManager = $serviceManager;
        $this->id = $object->getId();
        $this->objectType = get_class($object);
        $this->shortObjectType = (new ReflectionClass($object))->getShortName();
        $this->stateUpdater = new StateUpdateHelper($serviceManager, $object);
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

    private function debugObject(
        bool $debug,
        string $message = "",
        string $strShortType = "Tile",
        int $id = 0
    ) {
        if (!$debug) {
            return;
        }
        if ($this->shortObjectType == $strShortType && ($this->id == $id || $id == 0)) {
            $this->log("$message {$this->shortObjectType}:{$this->id}");
        }
    }

    public function request(array $eventInfo): StateInterface
    {

        $event = $eventInfo['event'];
        $source = $eventInfo['source'];
        $this->debugObject(false, "Requesting state for event $event from $source");
        do {
            $this->restoreState();
            $current_state = $this->__state;
            $stateClass = $current_state::StateClass()->getShortName();
            $this->debugObject(false, "$stateClass");
            $this->setState($current_state->handleRequest($eventInfo));
            $changed_state = $this->__state;
            if ($changed_state != $current_state) {
                $this->stateUpdater->saveState($changed_state::StateClass());
            }
            $eventInfo = Constants::EMPTY_EVENT;
        } while ($current_state != $changed_state);
        return $changed_state;
    }

    protected function restoreState(): void
    {
        $rflState = $this->stateUpdater->readState();
        $staRegistered = StatesLocalCache::findRegisteredStateInstance($rflState, $this->id);
        $this->stateUpdater->saveState($rflState);
        $this->setState($staRegistered::StateClass());
    }

    public function reset(): void
    {
        StatesLocalCache::reset(); // todo: try to reset speific object's states only
        $this->stateUpdater->saveState(null);
    }

}
