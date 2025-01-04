<?php

namespace App\Models\Components;

use App\Utils\CaseConverters;
use App\Utils\ReflectionUtils;

abstract class StateViewComponent extends Component implements IState, IView
{
	protected $fillable = ['id'];
	protected $view_name = 'web-renderer.default';

	public static function state(): string|null
	{
		return null;
	}

	public function handleStateEvent(array $event): string|null
	{
		$eventName = $event['event'];
		$eventData = $event['data'];
		$source = $event['source'];
		$destination = $event['destination'];
		if ($eventName === null || $eventName === '' || $eventName === 'reload') {
			$pass = $this->passTo();
			echo "Passing to $pass" . PHP_EOL;
			$this->super->gameObject->update(['state' => $pass]);
			return $pass;
		}
		$method = 'on' . CaseConverters::snakeToPascal($eventName) . 'Event';
		$nextState = $this->passTo();
		if (method_exists($this, $method)) {
			$nextState = ReflectionUtils::invokeMethod($this, $method, $eventData);
			$this->super->gameObject->update(['state' => $nextState]);
		}
		if (!$nextState) {
			//$nextState = $this->super->gameObject->state;
			$nextState = get_class($this)::state();
			echo "StateViewComponent: No method found for event $eventName" . PHP_EOL;
		}
		$this->super->gameObject->update(['state' => $nextState]);

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
		return get_class($this)::state();
		//return null;
	}

	public function view()
	{
		$data = $this->messages();
		if (!isset($this->view_name)) {
			$this->view_name = 'web-renderer.default';
		}
		$view = view($this->view_name, $data);
		return $view;
	}
}
