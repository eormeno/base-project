<?php

namespace App\Listeners;

use App\Models\Game;
use App\Events\GameEvent;
use App\Models\GameObject\GameObject;
use App\Contracts\IGameEventListener;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Events\GameEventListenerManager;

class GameEventListener implements IGameEventListener
{
	use \App\Traits\DebugHelper;

	public function handle(GameEvent $frontEvent): void
	{
		$listeners = GameEventListenerManager::componentListenersOf($frontEvent);
		foreach ($listeners as $component) {
			$component->handleGameEvent($frontEvent);
		}
		// $targetedGameObjects = $this->getTargetedGameObjects($frontEvent->game, $frontEvent->event);
		// $this->handleTargetedGameObjects($targetedGameObjects, $frontEvent->event);
	}

	private function handleTargetedGameObjects(Collection $targetedGameObjects, array $eventInfo): void
	{
		foreach ($targetedGameObjects as $gameObject) {
			$gameObject->handle($eventInfo);
		}
	}

	private function getTargetedGameObjects(Game $game, array $eventInfo): Collection
	{
		$target = $eventInfo['destination'] ?? null;
		if ($target) {
			return new Collection([GameObject::find($target)]);
		}
		return GameObject::activesOfGame($game)->get();
	}

}
