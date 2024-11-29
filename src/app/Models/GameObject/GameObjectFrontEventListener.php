<?php

namespace App\Models\GameObject;

use App\Events\FrontEvent;
use App\Contracts\IFrontEventListener;

class GameObjectFrontEventListener extends GameObjectStateContext implements IFrontEventListener
{

    public function handle(FrontEvent $event): void
    {
        if (!$this->active) {
            return;
        }
        $this->log("GameObject ($this->name) handling event '{$event->event['event']}'");
        $this->request($event->event);
    }

}
