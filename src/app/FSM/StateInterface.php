<?php

namespace App\FSM;

use ReflectionClass;

interface StateInterface
{
    public static function StateClass(): ReflectionClass;
    public function isNeedRestoring(): bool;
    public function setNeedRestoring(bool $value): void;
    public function setContext(StateContextInterface $content);
    public function setManagedModel(IStateManagedModel $model);
    public function onReload(): void;
    public function onSave(): void;
    public function onEnter(): void;
    public function onRefresh(): void;
    public function onExit(): void;
    public function passTo();
    public function handleRequest(array $event): ReflectionClass;
    public function view(string $controllerKebabCaseName);
}
