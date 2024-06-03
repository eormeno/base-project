<?php

namespace App\Services\GuessTheNumber;

abstract class AbstractService
{
    public function __construct(
        protected ServiceManager $serviceManager,
    ) {
    }
}
