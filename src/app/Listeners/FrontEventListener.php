<?php

namespace App\Listeners;

use App\Events\FrontEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FrontEventListener
{
    /**
     * Handle the event.
     */
    public function handle(FrontEvent $event): void
    {
        $event->game->handle($event);
        $event->game->gameObject->handle($event);
    }
}
