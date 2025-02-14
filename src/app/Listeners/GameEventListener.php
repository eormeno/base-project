<?php

namespace App\Listeners;

use App\Events\GameEvent;
use App\Contracts\IGameEventListener;
use App\Models\Events\GameEventListenerManager;
use App\Traits\DebugHelper;

class GameEventListener implements IGameEventListener
{
	use DebugHelper;
	public function handle(GameEvent $frontEvent): void
	{
		$startTime = microtime(true);
		$listeners = GameEventListenerManager::listenersOf($frontEvent);
		$endTime = microtime(true);
		$duration = ceil(($endTime - $startTime) * 1000);

		$this->log("Listeners resolved in $duration ms");

		foreach ($listeners as $listener) {
			$listener->handle($frontEvent);
		}
	}
}
