<?php

namespace App\FSM;

interface StateInterface
{
    public static function dashCaseName();
    public function setContext(StateContextInterface $content);
    public function onEnter(): void;
    public function onExit(): void;
    public function passTo(): void;
    public function handleRequest(?string $event = null, $data = null);
    public function toArray(): array;
    public function view();
}
