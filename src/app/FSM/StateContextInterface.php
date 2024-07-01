<?php

namespace App\FSM;

interface StateContextInterface
{
    public function setParent(StateContextInterface $parent): void;

    public function getParent(): StateContextInterface;

    public function addChild(IStateManagedModel $child): string;

    public function addChildren(array $children): array;

    public function getChildren(): array;

    public function hasChildren(): bool;

    public function getChildContext(string $alias): StateContextInterface;

    public function removeChild(IStateManagedModel $child): void;

    public function request(array $event): StateInterface;

    public function __get($name);

}
