<?php

namespace App\Models\GameObject;

use App\Utils\Constants;
use App\Events\GameEvent;
use App\Contracts\IStateContext;
use App\Models\Components\Component;

/**
 * Handles the front incoming event managing the GO's state following the State-Design-Pattern.
 */
abstract class StateContextBase extends Base implements IStateContext//, IFrontEventListener
{
	public function handle(array $eventInfo): void
	{
		if (!$this->isActive()) {
			return;
		}
		// $this->log('StateContextBase::handle ' . json_encode($eventInfo));
		if (!$this->isStateManaged()) {
			return;
		}
		$this->request($eventInfo);
	}

	public function handleEventOfComponent(GameEvent $event, Component $component)
	{
		$this->log('handle event "' . $event->event['event'] . '" of component "' . $component . '"');
	}

	public function request(array $event)
	{
		do {
			$stateComponent = $this->currentStateComponent();
			if (!$stateComponent) {
				// TODO quitar este return
				return;
			}
			$stateName = $this->state;
			$stateComponent->onStart();
			$nextState = $stateComponent->handleEvent($event);
			$this->changeState($nextState);
			$event = Constants::EMPTY_EVENT;
		} while ($nextState !== $stateName);
	}
}
