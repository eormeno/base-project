<?php

namespace App\Services;

abstract class AbstractServiceManager
{
    protected $services = [];

    public function __get($name)
    {
        if (!isset($this->services[$name])) {
            throw new \Exception("Service $name not found");
        }
        return $this->services[$name];
    }
}
