<?php

namespace App\Services;

use App\FSM\StateContextInterface;

abstract class AbstractServiceComponent
{
    public function __construct(
        protected AbstractServiceManager $serviceManager,
    ) {
    }

    protected function sendEvent(
        StateContextInterface $context,
        string $event,
        string|null $destination = null,
        array $data = []
    ) {
        $event = [
            'event' => $event,
            'source' => $context->alias,
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
