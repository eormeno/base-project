<?php

namespace App\Models\GameObject;

use App\Contracts\IStateContext;
use App\Models\Components\IState;

class GameObjectStateContext extends GameObjectBase implements IStateContext
{
    public function request(array $event)
    {
        $this->componentsIterator(
            function ($component, $subclass) use ($event) {
                if ($subclass->state() !== $this->state) {
                    return;
                }
                if ($subclass->enabled === false) {
                    $subclass->enabled = true;
                    $subclass->onEnter();
                }
                $subclass->handle($event);
                return;
            },
            IState::class
        );
    }
}
