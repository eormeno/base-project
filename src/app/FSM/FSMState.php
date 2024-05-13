<?php

namespace App\FSM;

interface FSMState
{
    public function handleRequest(FSMContext $context, $event = null, $data = null);
}
