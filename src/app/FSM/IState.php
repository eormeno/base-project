<?php

namespace App\FSM;

use ReflectionClass;

interface IState
{
    public static function StateClass(): ReflectionClass;
    public function setContext(IStateContext $content);
    public function setStateModel(IStateModel $model);
    public function getStateModel(): IStateModel;
    public function reset(): void;
    public function onEnter(): void;
    public function onRefresh(): void;
    public function onExit(): void;
    public function passTo();
    public function handleRequest(array $event): ReflectionClass;
    public function view(string $controllerKebabCaseName);
    public function addChild(IStateModel $model, string $viewId): string;
    public function removeChild(string $strAlias): void;
    public function addChilren(array $models, string $viewId): array;
    public function getChildren(): array;
    public function defineSubStates(): void;
}
