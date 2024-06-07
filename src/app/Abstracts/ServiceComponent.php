<?php

namespace App\Abstracts;

abstract class ServiceComponent
{
    public function __construct(
        protected ServiceManager $serviceManager,
    ) {
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return $this->serviceManager->$name;
    }
}
