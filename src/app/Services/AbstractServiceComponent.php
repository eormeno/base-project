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

    protected final function sendEvent(
        IStateManagedModel $model,
        string $event,
        string|null $destination = null,
        array $data = []
    ) {
        $this->_sendEvent($model->getAlias(), $event, $destination, $data);
    }

    private function _sendEvent(
        string $modelAlias,
        string $event,
        string|null $destination = null,
        array $data = []
    ) {
        $event = [
            'event' => $event,
            'source' => $modelAlias,
            'data' => $data,
            'destination' => $destination,
        ];
        $this->serviceManager->stateManager->enqueueEvent($event);
    }

    protected final function requireRefresh(IStateManagedModel $model)
    {
        $this->_requireRefresh($model->getAlias());
    }

    protected final function _requireRefresh(string $alias)
    {
        $this->serviceManager->stateManager->requireRefresh($alias);
        $this->_sendEvent($alias, 'refresh');
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return $this->serviceManager->get($name);
    }
}
