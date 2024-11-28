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
                $subclass->handle($event);
            },
            IState::class
        );
    }
}
