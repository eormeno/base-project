<?php

namespace App\FSM;

interface IStateContext
{
    public function request(array $event): IState;

    public function __get($name);

}
