<?php

namespace App\Models\Components;

use App\Utils\CaseConverters;
use App\Utils\ReflectionUtils;

abstract class StateViewComponent extends Component implements IState
{
	protected $fillable = ['id'];
	protected $view_name = 'default';

	public function handleStateEvent(array $event): string|null
	{
		$eventName = $event['event'];
		$eventData = $event['data'];
		$source = $event['source'];
		$destination = $event['destination'];
		$currentState = $this->super->gameObject->state;
		$nextState = $this->passTo();

		if (!in_array($eventName, [null, '', 'reload'])) {
			$method = 'on' . CaseConverters::snakeToPascal($eventName) . 'Event';
			if (method_exists($this, $method)) {
				$nextState = ReflectionUtils::invokeMethod($this, $method, $eventData);
			}
		}

		if (!$nextState) {
			$nextState = $currentState;
		}

		// TODO Validate the next state is a valid state for the game object

		return $nextState;
	}

	public function onEnter(): void
	{
	}

	public function onExit(): void
	{
	}

	public function passTo(): string|null
	{
		return null;
	}

	public function view()
	{
		$data = $this->messages();
		if (!isset($this->view_name)) {
			$this->view_name = 'default';
		}
		return base64_encode(view($this->view_name, $data));
	}
}
