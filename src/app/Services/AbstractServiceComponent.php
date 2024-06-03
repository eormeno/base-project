<?php

namespace App\Services;

abstract class AbstractServiceComponent
{
    public function __construct(
        protected AbstractServiceManager $serviceManager,
    ) {
    }

    public function __get($name)
    {
        if (!isset($this->serviceManager->$name)) {
            throw new \Exception("Service $name not found");
        }
        return $this->serviceManager->$name;
    }
}
