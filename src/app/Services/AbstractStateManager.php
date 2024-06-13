<?php

namespace App\Services;

use App\FSM\IStateManagedModel;
use App\Services\StateContextImpl;

abstract class AbstractStateManager
{
    protected array $statesMap = [];
    protected AbstractServiceManager $serviceManager;

    public function service(string $name): AbstractServiceComponent
    {
        return $this->serviceManager->get($name);
    }

    public final function getState(
        IStateManagedModel $object,
        array $eventInfo = ['event' => null, 'data' => null],
        string $strControllerKebabCaseName
    ) {
        $stateContext = $this->getStateContext($object);
        return $stateContext->request($eventInfo)->view($strControllerKebabCaseName);
    }

    private function getStateContext(IStateManagedModel $object)
    {
        $modelClassName = get_class($object);
        if (!isset($this->statesMap[$modelClassName])) {
            throw new \Exception("State for $modelClassName is not defined.");
        }
        $stateContext = $this->statesMap[$modelClassName]['state_context'];
        if (!$stateContext) {
            $stateContext = new StateContextImpl($this->serviceManager, $object);
            $this->statesMap[$modelClassName]['state_context'] = $stateContext;
        }
        return $stateContext;
    }

    public final function reset(IStateManagedModel $object)
    {
        $stateContext = $this->getStateContext($object);
        $stateContext->reset();
    }
}
