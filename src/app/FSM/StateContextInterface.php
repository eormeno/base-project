<?php

namespace App\FSM;

interface StateContextInterface
{
    public function setParent(StateContextInterface $parent): void;

    public function getParent(): StateContextInterface;

    public function addChild(StateContextInterface $child): void;

    public function getChildren(): array;

    public function removeChild(StateContextInterface $child): void;

    public function request(array $event): StateInterface;

    public function __get($name);

}
