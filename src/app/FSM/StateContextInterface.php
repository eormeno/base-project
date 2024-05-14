<?php

namespace App\FSM;

interface StateContextInterface
{
    public function setState(StateInterface $state);

    public function request($event = null, $data = null);

    public function setValue(string $key, $value);

    public function getValue(string $key);

}
