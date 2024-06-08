<?php

namespace App\FSM;

use ReflectionClass;

interface StateInterface
{
    public static function StateClass(): ReflectionClass;
    public function isNeedRestoring(): bool;
    public function setNeedRestoring(bool $value): void;
    public function setContext(StateContextInterface $content);
    public function onEnter(bool $restoring): void;
    public function onRefresh(): void;
    public function onExit(): void;
    public function passTo(): ReflectionClass;
    public function handleRequest(?string $event = null, $data = null);
    public function toArray(): array;
    public function view();
}
