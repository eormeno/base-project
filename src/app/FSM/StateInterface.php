<?php

namespace App\FSM;

interface StateInterface
{
    public function name();
    public function handleRequest(StateContextInterface $context, $event = null, $data = null);
    public static function fromName($namespace, $name);
}
