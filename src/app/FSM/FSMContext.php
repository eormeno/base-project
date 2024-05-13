<?php

namespace App\FSM;

interface FSMContext
{
    public function setState(FSMState $state);

    public function request($event = null, $data = null);

    public function setValue(string $key, $value);

    public function getValue(string $key);

}
