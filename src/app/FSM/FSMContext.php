<?php

namespace App\FSM;

use App\Http\Controllers\GTNStates\GTNState;

interface FSMContext
{
    public function setState(GTNState $state);

    public function request($event = null, $data = null);

    public function setValue(string $key, $value);

    public function getValue(string $key);

}
