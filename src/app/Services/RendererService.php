<?php

namespace App\Services;

use App\Models\Game;
use App\Events\FrontEvent;
use App\Traits\DebugHelper;
use App\Contracts\IRenderer;
use App\Models\GameObject\GameObject;

class RendererService implements IRenderer
{
	use DebugHelper;

	public function render(Game $game, array $eventInfo): array
	{
		$currentTimestamp = microtime(true);
		event(new FrontEvent($game, $eventInfo));
		$result = $this->request($game, $eventInfo);
		$elapsed = ceil((microtime(true) - $currentTimestamp) * 1000);
		$result['elapsed'] = $elapsed;
		return $result;
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
		$views = $this->resolveActiveGameObjectsViews($game, $rendered);
		foreach ($views as $id => $view) {
			$ret[$id] = $view;
		}
		$actives = $this->resolveActiveGOIds($game);
		$deactives = $this->resolveDeactives($rendered, $actives);
		if (!empty($deactives)) {
			$ret['deactives'] = $deactives;
		}
		return $ret;
	}

	private function resolveActiveGOIds(Game $game)
	{
		$actives = collect(GameObject::activesOfGame($game)->get())->pluck('id')->toArray();
		// remove the root game object id from the list
		$actives = array_values(array_diff($actives, [$game->gameObject->id]));
		return $actives;
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

	private function resolveActiveGameObjectsViews(Game $game, array $rendered): array
	{
		$gameObject = $game->gameObject;
		$gameObjects = GameObject::activesOfGame($game)->get();
		$views = [];
		foreach ($gameObjects as $gameObject) {
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
