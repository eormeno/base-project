<?php

namespace App\Listeners;

use App\Events\FrontEvent;
use App\Contracts\IFrontEventListener;

class FrontEventListener implements IFrontEventListener
{

    public function handle(FrontEvent $event): void
    {
        // $event->game->handle($event);
        $event->game->gameObject->handle($event);
    }
}
