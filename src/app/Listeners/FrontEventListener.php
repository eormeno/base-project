<?php

namespace App\Listeners;

use App\Events\FrontEvent;

class FrontEventListener
{

    public function handle(FrontEvent $event): void
    {
        // $event->game->handle($event);
        $event->game->gameObject->handle($event);
    }
}
