<?php

namespace App\Services;

use App\Services\StateContextImpl;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractStateManager
{
    protected array $statesMap = [];
    protected AbstractServiceManager $serviceManager;

    public function service(string $name): AbstractServiceComponent
    {
        return $this->serviceManager->get($name);
    }

    public final function getState(Model $object, array $eventInfo = ['event' => null, 'data' => null])
    {
        $stateContext = $this->getStateContext($object);
        return $stateContext->request($eventInfo)->view();
    }

    private function getStateContext(Model $object)
    {
        $modelClassName = get_class($object);
        if (!isset($this->statesMap[$modelClassName])) {
            throw new \Exception("State for $modelClassName is not defined.");
        }
        $stateConfig = $this->statesMap[$modelClassName];
        $stateContext = $this->statesMap[$modelClassName]['state_context'];
        if (!$stateContext) {
            $stateContext = new StateContextImpl($this->serviceManager, $object, $stateConfig);
            $this->statesMap[$modelClassName]['state_context'] = $stateContext;
        }
        return $stateContext;
    }

    public final function reset(Model $object)
    {
        $stateContext = $this->getStateContext($object);
        $stateContext->reset();
    }
}
