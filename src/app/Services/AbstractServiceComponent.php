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
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return $this->serviceManager->get($name);
    }
}
