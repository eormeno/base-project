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
        // $check = $event['event'] === 'another_challenge';
        // $iterations = 0;

        do {
            $current_state_component = $this->currentStateComponent();
            $current_state_name = $current_state_component::state();
            $current_state_component->onStart();
            // if ($check && $iterations > 0) {
            //     dd($next_state_component, $current_state_component);
            // }
            $next_state_name = $current_state_component->handleStateEvent($event);
            $next_state_component = $this->findComponentForState($next_state_name);
            $this->current_state = $next_state_component;
            $this->log("Transitioning from $current_state_name to $next_state_name");
            // $iterations++;
            $event = Constants::EMPTY_EVENT;
        } while ($next_state_name !== $current_state_name);

    }
}
