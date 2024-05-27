<?php

namespace App\FSM;

interface StateInterface
{
    public static function name();
    public function setContent(StateContextInterface $content);
    public function handleRequest(?string $event = null, $data = null);
    public static function fromName($namespace, $name);
}
