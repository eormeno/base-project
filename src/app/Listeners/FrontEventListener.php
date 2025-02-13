<?php

namespace App\Listeners;

use App\Models\Game;
use App\Events\FrontEvent;
use App\Traits\DebugHelper;
use App\Models\GameObject\GameObject;
use App\Contracts\IFrontEventListener;
use Illuminate\Database\Eloquent\Collection;

class FrontEventListener implements IFrontEventListener
{

	use DebugHelper;

	public function handle(FrontEvent $frontEvent): void
	{
		$targetedGameObjects = $this->getTargetedGameObjects($frontEvent->game, $frontEvent->event);
		$this->handleTargetedGameObjects($targetedGameObjects, $frontEvent->event);
		// $this->log("FrontEventListener::handle " . $frontEvent);
		// $event->game->handle($event);
		// TODO Acá debería enviar el evento a todos los GameObjects del juego en forma recursiva.
		//$event->game->gameObject->handle($event);
		// itera todos los gameObjects activos del juego
		// y les envía el evento
		// $gameObjects = GameObject::activesOfGame($event->game)->get();
		// // TODO agregar también un filtro de sólo los game objects que son menajados por eventos.
		// foreach ($gameObjects as $gameObject) {
		// 	// $gameObject->handle($event);
		// }
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
