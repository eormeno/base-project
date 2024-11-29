<?php

namespace App\Listeners;

use App\Events\FrontEvent;
use App\Contracts\IFrontEventListener;

class FrontEventListener implements IFrontEventListener
{

    public function handle(FrontEvent $event): void
    {
        // $event->game->handle($event);
        // TODO Acá debería enviar el evento a todos los GameObjects del juego en forma recursiva.
        $event->game->gameObject->handle($event);
    }
}
