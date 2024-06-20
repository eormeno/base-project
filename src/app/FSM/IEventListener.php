<?php

namespace App\FSM;

interface IEventListener
{
    public function onEvent(StateChangedEvent $event): void;
}
