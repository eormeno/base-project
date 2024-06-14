<?php

namespace App\Services;

use App\FSM\IStateManagedModel;
use App\Services\StateContextImpl;

class StateManager
{
    protected array $statesMap = [];

    public final function getStatesViews(
        AbstractServiceManager $serviceManager,
        IStateManagedModel $object,
        array $eventInfo = ['event' => null, 'data' => null],
        string $strControllerKebabCaseName
    ) {
        $stateContext = $this->getStateContext($serviceManager, $object);
        return $stateContext->request($eventInfo)->view($strControllerKebabCaseName);
    }

    private function getStateContext(AbstractServiceManager $serviceManager, IStateManagedModel $object)
    {
        $modelClassName = get_class($object);
        if (!isset($this->statesMap[$modelClassName])) {
            $this->statesMap[$modelClassName] = ['state_context' => null];
        }
        $stateContext = $this->statesMap[$modelClassName]['state_context'];
        if (!$stateContext) {
            $stateContext = new StateContextImpl($serviceManager, $object);
            $this->statesMap[$modelClassName]['state_context'] = $stateContext;
        }
        return $stateContext;
    }

    public final function reset(AbstractServiceManager $serviceManager, IStateManagedModel $object)
    {
        $stateContext = $this->getStateContext($serviceManager, $object);
        $stateContext->reset();
    }
}
