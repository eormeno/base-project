<?php

namespace App\FSM;

use ReflectionClass;

interface StateContextInterface
{

    public function setState(ReflectionClass $state_class): void;

    public function request(?string $event = null, $data = null): StateInterface;

    public function __get($name);

}
