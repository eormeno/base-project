<?php

namespace App\FSM;

interface StateContextInterface
{
    public function setState(StateInterface $state);

    public function request($event = null, $data = null);

    public function __get($name);

    public function __set($name, $value);

}
