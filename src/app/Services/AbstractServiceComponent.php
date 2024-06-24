<?php

namespace App\Services;

use App\FSM\IStateManagedModel;
use App\FSM\StateContextInterface;

abstract class AbstractServiceComponent
{
    public function __construct(
        protected AbstractServiceManager $serviceManager,
    ) {
    }

    protected function sendEvent(
        IStateManagedModel $model,
        string $event,
        string|null $destination = null,
        array $data = []
    ) {
        $event = [
            'event' => $event,
            'source' => $model->getAlias(),
            'data' => $data,
            'destination' => $destination,
        ];
        $this->serviceManager->stateManager->enqueueEvent($event);
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return $this->serviceManager->get($name);
    }
}
