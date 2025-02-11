<?php

namespace App\Services;

use App\Models\Game;
use App\Events\FrontEvent;
use App\Traits\DebugHelper;
use App\Contracts\IRenderer;
use App\Models\GameObject\GameObject;
use Illuminate\Database\Eloquent\Collection;

class RendererService implements IRenderer
{
	use DebugHelper;

	public function render(Game $game, array $eventInfo): array
	{
		$currentTimestamp = microtime(true);
		$targetedGameObjects = $this->getTargetedGameObjects($game, $eventInfo);

		foreach ($targetedGameObjects as $gameObject) {
			$gameObject->handle($eventInfo);
		}

		$result = $this->request($game, $eventInfo);
		$elapsed = ceil((microtime(true) - $currentTimestamp) * 1000);
		$result['elapsed'] = $elapsed;
		return $result;
	}

	private function getTargetedGameObjects(Game $game, array $eventInfo): Collection
	{
		$target = $eventInfo['destination'] ?? null;
		if ($target) {
			return new Collection([GameObject::find($target)]);
		}
		return GameObject::activesOfGame($game)->get();
	}

	private function request(Game $game, array $event): array
	{
		$jsonClient = $game->gameApp->client == 'webgl';
		$rootGameObject = $game->gameObject;
		$rendered = $event['rendered'];
		$ret = [
			'elapsed' => 0,
		];
		if (!$jsonClient) {
			$ret['root'] = $rootGameObject->id;
		}

		// Obtener los objetos activos una sola vez
		$activeGameObjects = GameObject::activesOfGame($game)->get();

		$views = $this->resolveActiveGameObjectsViews($activeGameObjects, $game, $rendered);
		foreach ($views as $id => $view) {
			$ret[$id] = $view;
		}
		$actives = $this->resolveActiveGOIds($activeGameObjects, $game);
		$deactives = $this->resolveDeactives($rendered, $actives);
		if (!empty($deactives)) {
			$ret['deactives'] = $deactives;
		}
		return $ret;
	}

	private function resolveActiveGOIds($activeGameObjects, $rootId): array
	{
		return collect($activeGameObjects)
			->pluck('id')
			->reject(fn($id) => $id === $rootId)
			->values()
			->toArray();
	}

	private function resolveDeactives(array $rendered, array $actives): array
	{
		$deactives = [];
		if (empty($rendered)) {
			return $deactives;
		}
		foreach ($rendered as $id => $version) {
			if (!in_array($id, $actives)) {
				$deactives[] = (string) $id;
			}
		}
		return $deactives;
	}

	private function resolveActiveGameObjectsViews($activeGameObjects, Game $game, array $rendered): array
	{
		$gameObject = $game->gameObject;
		// $gameObjects = GameObject::activesOfGame($game)->get();
		$views = [];
		foreach ($activeGameObjects as $gameObject) {
			$view = $gameObject->view();
			if (empty($view)) {
				continue;
			}
			// if the game object is already rendered in renderedVersions, and its version is the same, skip it
			if (isset($rendered[$gameObject->id]) && $rendered[$gameObject->id] == $gameObject->version) {
				continue;
			}
			$views[$gameObject->id] = $view;
		}
		return $views;
	}

}
