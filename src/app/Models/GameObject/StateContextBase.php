<?php

namespace App\Models\GameObject;

use App\Utils\Constants;
use App\Events\FrontEvent;
use App\Contracts\IStateContext;
use App\Contracts\IFrontEventListener;

/**
 * Handles the front incoming event, trying to manage the GO's state following the State-Design-Pattern.
 */
abstract class StateContextBase extends Base implements IStateContext, IFrontEventListener
{

	public function handle(FrontEvent $event): void
	{
		if (!$this->active) {
			return;
		}
		$this->request($event->event);
	}

	public function request(array $event)
	{
		do {
			// TODO para que sean iguales, se debe revisar el bucle
			$current_state_component = $this->currentStateComponent();
			$current_state_component_2 = $this->currentStateComponent_2();
			$current_state_name = $current_state_component::state();
			$current_state_name_2 = $this->state;
			echo "Current state component: $current_state_component->id ($current_state_name) $current_state_component_2->id ($current_state_name_2)" . PHP_EOL;
			$current_state_component->onStart();
			$next_state_name = $current_state_component->handleStateEvent($event);
			$next_state_component = $this->findComponentForState($next_state_name);
			$this->current_state = $next_state_component;
			$event = Constants::EMPTY_EVENT;
		} while ($next_state_name !== $current_state_name);
	}
}
