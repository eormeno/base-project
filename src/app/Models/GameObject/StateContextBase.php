<?php

namespace App\Models\GameObject;

use App\Utils\Constants;
use App\Events\FrontEvent;
use App\Contracts\IStateContext;
use App\Contracts\IFrontEventListener;

/**
 * Handles the front incoming event managing the GO's state following the State-Design-Pattern.
 */
abstract class StateContextBase extends Base implements IStateContext, IFrontEventListener
{

	public function handle(FrontEvent $event): void
	{
		if (!$this->active) {
			return;
		}
		// echo $event . PHP_EOL;
		$this->request($event->event);
	}

	public function request(array $event)
    {
        do {
			$stateName = $this->state;
            $stateComponent = $this->currentStateComponent();
            $stateComponent->onStart();
            $nextState = $stateComponent->handleStateEvent($event);
			//$this->update(['state' => $nextState]);
			$this->changeState($nextState);
            $event = Constants::EMPTY_EVENT;
        } while ($nextState !== $stateName);
    }
}
