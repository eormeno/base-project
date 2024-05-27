<?php

namespace App\FSM;

interface StateInterface
{
    public static function name();
    public function setContext(StateContextInterface $content);
    public function start();
    public function handleRequest(?string $event = null, $data = null);
}
