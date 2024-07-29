<?php

namespace App\Services;

use App\FSM\IState;
use ReflectionClass;
use App\FSM\IStateModel;
use App\Utils\Constants;
use App\FSM\IStateContext;
use App\Traits\DebugHelper;
use Illuminate\Support\Carbon;
use App\Helpers\StatesLocalCache;
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

    private bool $firstTime = true;

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

    private function setState(ReflectionClass $rflStateClass): IState
    {
        $stateInstance = StatesLocalCache::getStateInstance($rflStateClass, $this->id);
        $stateInstance->setContext($this);
        $stateInstance->setStateModel($this->object);
        if ($stateInstance->isNeedRestoring()) {
            $stateInstance->setNeedRestoring(false);
            $stateInstance->reset();
            $stateInstance->onReload();
        }
        if ($this->__state && $this->__state != $stateInstance) {
            $this->__state->onExit();
            $this->stateUpdater->setEnteredAt(null);
        }
        if (!$this->stateUpdater->getEnteredAt()) {
            $stateInstance->reset();
            $stateInstance->onEnter();
            $this->stateUpdater->setEnteredAt(Carbon::now());
        }
        $stateInstance->enteredAt = $this->stateUpdater->getEnteredAt();
        $this->__state = $stateInstance;
        return $stateInstance;
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
        $eventDestination = $eventInfo['destination'];
        $initialState = null;
        $currentState = null;
        $firstTime = true;
        do {
            $currentState = $this->restoreState();
            if ($firstTime) {
                $firstTime = false;
                $initialState = $currentState;
                $initialName = $currentState::StateClass()->getShortName();
                $this->log("Initial state is $initialName");
            }
            $changedState = $this->setState($currentState->handleRequest($eventInfo));
            $changedName = $changedState::StateClass()->getShortName();
            $this->log("State changed to $changedName");
            $this->stateUpdater->saveState($changedState::StateClass());
            $eventInfo = Constants::EMPTY_EVENT;
        } while ($currentState != $changedState);
        $this->isStateChanged = $initialState != $changedState;
        if ($eventDestination == $this->object->getAlias()) {
            $changedState->onRefresh();
        }
        return $changedState;
    }

    protected function restoreState(): IState
    {
        $rflState = $this->stateUpdater->readState();
        $staRegistered = StatesLocalCache::findRegisteredStateInstance($rflState, $this->id);
        $this->stateUpdater->saveState($rflState);
        return $this->setState($staRegistered::StateClass());
    }
}
