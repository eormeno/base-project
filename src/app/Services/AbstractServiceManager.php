<?php

namespace App\Services;

abstract class AbstractServiceManager
{
    protected $services = [];

    protected function addService($name, $service)
    {
        $this->services[$name] = $service;
    }

    protected function hasService($name)
    {
        return isset($this->services[$name]);
    }

    protected function getService($name)
    {
        return $this->services[$name];
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if ($this->hasService($name)) {
            return $this->getService($name);
        }

        throw new \Exception("Property $name does not exist");
    }
}
