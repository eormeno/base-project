<?php

namespace App\Services;

use App\FSM\IState;
use ReflectionClass;
use App\FSM\IStateModel;
use App\Utils\Constants;
use App\FSM\IStateContext;
use App\Models\AStateModel;
use Illuminate\Support\Carbon;
use App\Helpers\StatesLocalCache;
// use App\Helpers\StateUpdateHelper;
use App\Services\AbstractServiceManager;
use App\Services\AbstractServiceComponent;

class StateContextImpl extends AbstractServiceComponent implements IStateContext
{
    protected ?IState $__state = null;
    protected AbstractServiceManager $serviceManager;
    // protected StateUpdateHelper $stateUpdater;
    protected StateManager $stateManager;
    protected AStateModel $stateModel;
    protected int $id;
    public bool $isStateChanged = false;

    public function __construct(
        AbstractServiceManager $serviceManager,
        AStateModel $stateModel
    ) {
        $this->serviceManager = $serviceManager;
        $this->stateManager = $serviceManager->stateManager;
        $this->stateModel = $stateModel;
        $this->id = $stateModel->id;
        // $this->stateUpdater = new StateUpdateHelper($serviceManager, $stateModel);
    }

    private function setState(ReflectionClass $rflStateClass): IState
    {
        if ($this->__state && $this->__state::StateClass() == $rflStateClass) {
            return $this->__state;
        }
        $stateInstance = StatesLocalCache::getStateInstance($rflStateClass, $this->id);
        $stateInstance->setContext($this);
        $stateInstance->setStateModel($this->stateModel);
        if ($this->__state && $this->__state != $stateInstance) {
            $this->__state->onExit();
            //$this->stateUpdater->setEnteredAt(null);
            $this->stateModel->entered_at = null;
        }
        //if (!$this->stateUpdater->getEnteredAt()) {
        if (!$this->stateModel->entered_at) {
            $stateInstance->reset();
            $stateInstance->onEnter();
            //$this->stateUpdater->setEnteredAt(Carbon::now());
            $this->stateModel->entered_at = Carbon::now();
        }
        //$stateInstance->enteredAt = $this->stateUpdater->getEnteredAt();
        $stateInstance->enteredAt = $this->stateModel->entered_at;
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
        // $stateWasNull = $this->stateModel->_getState() == null;
        $stateFirstTime = $this->stateModel->entered_at == null;
        $destination = $eventInfo['destination'];
        $initialState = null;
        $currentState = null;
        $firstTime = true;
        $hasIntermediateChange = false;
        do {
            $currentState = $this->restoreState();
            if ($firstTime) {
                $firstTime = false;
                $initialState = $currentState;
            }
            $newState = $currentState->handleRequest($eventInfo);
            $changedState = $this->setState($newState);
            $hasIntermediateChange |= $currentState != $changedState;
            //$this->stateUpdater->saveState($changedState::StateClass());
            $this->stateModel->updateState($changedState::StateClass());
            $eventInfo = Constants::EMPTY_EVENT;
        } while ($currentState != $changedState);
        $this->isStateChanged = $initialState != $changedState ||
            $hasIntermediateChange ||
            //$stateWasNull ||
            $stateFirstTime;
        if ($destination == 'all' || $destination == $this->stateModel->getAlias()) {
            $changedState->onRefresh();
        }
        return $changedState;
    }

    protected function restoreState(): IState
    {
        //$rflState = $this->stateUpdater->readState();
        $rflState = $this->stateModel->currentState();
        $staRegistered = StatesLocalCache::findRegisteredStateInstance($rflState, $this->id);
        //$this->stateUpdater->saveState($rflState);
        $this->stateModel->updateState($rflState);
        return $this->setState($staRegistered::StateClass());
    }
}
