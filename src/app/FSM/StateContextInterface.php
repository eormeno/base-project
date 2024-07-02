<?php

namespace App\FSM;

interface StateContextInterface
{
    public function request(array $event): StateInterface;

    public function __get($name);

}
