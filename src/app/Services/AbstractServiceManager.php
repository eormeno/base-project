<?php

namespace App\Services;

abstract class AbstractServiceManager
{
    protected $services = [];

    protected function addService(string $name, AbstractServiceComponent $service): void
    {
        $this->services[$name] = $service;
    }

    protected function hasService(string $name): bool
    {
        return isset($this->services[$name]);
    }

    public function get(string $name): AbstractServiceComponent
    {
        if (!$this->hasService($name)) {
            throw new \Exception("Service $name does not exist");
        }
        return $this->services[$name];
    }
}
