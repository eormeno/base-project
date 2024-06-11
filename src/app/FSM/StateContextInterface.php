<?php

namespace App\FSM;

use ReflectionClass;

interface StateContextInterface
{

    public function request(array $event): StateInterface;

    public function reset(): void;

    public function __get($name);

}
