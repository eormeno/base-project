<?php

namespace App\FSM;

interface StateContextInterface
{

    public function setState($state_class): void;

    public function request(?string $event = null, $data = null): StateInterface;

    public function __get($name);

    public function __set($name, $value);

}
