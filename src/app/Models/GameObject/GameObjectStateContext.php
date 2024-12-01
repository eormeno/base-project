<?php

namespace App\Models\GameObject;

use App\Contracts\IStateContext;
use App\Models\Components\Component;

/**
 * Responsability: To handle the state of a game object following the State design pattern.
 */
class GameObjectStateContext extends GameObjectBase implements IStateContext
{
    public function request(array $event)
    {
        $current = $this->currentStateComponent();
        if ($current === null) {
            return;
        }
        $current->handle($event);
    }
}
