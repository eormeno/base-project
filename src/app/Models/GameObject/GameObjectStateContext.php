<?php

namespace App\Models\GameObject;

use App\Contracts\IStateContext;

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
        $current->onStart();
        $current_state = $current::state();
        $next_state = $current->handleStateEvent($event);
        if ($next_state !== $current_state) {
            $this->log("State must change to: '$next_state'");
        } else {
            $this->log("State remains the same: '$current_state'");
        }
    }
}
