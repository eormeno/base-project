<?php

namespace App\Models\GameObject;

use App\Contracts\IStateContext;
use App\Utils\Constants;

/**
 * Responsability: To handle the state of a game object following the State design pattern.
 */
class GameObjectStateContext extends GameObjectBase implements IStateContext
{
    public function request(array $event)
    {
        do {
            $current_state_component = $this->currentStateComponent();
            $current_state_name = $current_state_component::state();
            $current_state_component->onStart();
            $next_state_name = $current_state_component->handleStateEvent($event);
            $next_state_component = $this->findComponentForState($next_state_name);
            $this->current_state = $next_state_component;
            $this->log("Transitioning from $current_state_name to $next_state_name");
            $event = Constants::EMPTY_EVENT;
        } while ($next_state_name !== $current_state_name);
    }
}
