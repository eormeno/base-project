<?php

namespace App\FSM;

use ReflectionClass;

interface IState
{
    public static function StateClass(): ReflectionClass;
    public function isNeedRestoring(): bool;
    public function setNeedRestoring(bool $value): void;
    public function setContext(IStateContext $content);
    public function setManagedModel(IStateModel $model);
    public function onReload(): void;
    public function onSave(): void;
    public function onEnter(): void;
    public function onRefresh(): void;
    public function onExit(): void;
    public function passTo();
    public function handleRequest(array $event): ReflectionClass;
    public function view(string $controllerKebabCaseName);
}
