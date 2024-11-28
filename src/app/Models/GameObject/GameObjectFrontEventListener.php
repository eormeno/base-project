<?php

namespace App\Models\GameObject;

use App\Events\FrontEvent;
use App\Contracts\IFrontEventListener;

class GameObjectFrontEventListener extends GameObjectBase implements IFrontEventListener
{
    public function handle(FrontEvent $event): void
    {
        $this->log("Handling event {$event->event['event']}");
    }
}
