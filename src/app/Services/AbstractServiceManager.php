<?php

namespace App\Services;

use Exception;

abstract class AbstractServiceManager
{
    protected $services = [];
    protected ?EventManager $eventManager = null;
    protected ?StateManager $stateManager = null;

    protected function addService(string $name, AbstractServiceComponent $service): void
    {
        $this->services[$name] = $service;
    }

    protected function hasService(string $name): bool
    {
        return isset($this->services[$name]);
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            if ($name === 'eventManager' && $this->eventManager === null) {
                $this->eventManager = new EventManager();
            }
            if ($name === 'stateManager' && $this->stateManager === null) {
                $this->stateManager = new StateManager($this);
            }
            return $this->$name;
        }
        throw new Exception("Property $name does not exist");
    }

    public function get(string $name): AbstractServiceComponent
    {
        if (!$this->hasService($name)) {
            throw new Exception("Service $name does not exist");
        }
        return $this->services[$name];
    }
}
