<?php

namespace App\FSM;

interface StateContextInterface
{
    public function setParent(StateContextInterface $parent): void;

    public function getParent(): StateContextInterface;

    public function addChild(IStateManagedModel $child): StateContextInterface;

    public function addChildren(array $children): array;

    public function getChildren(): array;

    public function removeChild(IStateManagedModel $child): void;

    public function request(array $event): StateInterface;

    public function __get($name);

}
