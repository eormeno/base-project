<?php

namespace App\Listeners;

use App\Events\FrontEvent;
use App\Models\GameObject\GameObject;
use App\Contracts\IFrontEventListener;
use App\Traits\DebugHelper;

class FrontEventListener implements IFrontEventListener
{

	use DebugHelper;

	public function handle(FrontEvent $event): void
	{
		$this->log("FrontEventListener::handle " . $event);
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
}
