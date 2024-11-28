<?php

namespace App\Contracts;

use App\Events\FrontEvent;

interface IFrontEventListener
{
    public function handle(FrontEvent $event): void;
}
