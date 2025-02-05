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
		// $result['elapsed'] = $elapsed;
		return $result;
	}

	private function request(Game $game, array $event): array
	{
		$jsonClient = $game->gameApp->client == 'webgl';
		$rootGameObject = $game->gameObject;
		$rendered = $event['rendered'];
		$renderedVersions = $event['renderedVersions'];
		$ret = [
			'renderedVersions' => $renderedVersions,
			// 'elapsed' => 0,
			// 'root' => $rootGameObject->id,
		];
		$views = $this->resolveActiveGameObjectsViews($game, $rendered, $renderedVersions);
		foreach ($views as $id => $view) {
			$ret[$id] = $view;
		}
		$actives = $this->resolveActiveGOIds($game);
		// $ret['actives'] = $actives;
		//$ret['deactives'] = $event['rendered'];
		$deactives = $this->resolveDeactives($rendered, $renderedVersions, $actives);
		if (!empty($deactives)) {
			$ret['deactives'] = $deactives;
		}
		// $ret['deactives'] = $this->resolveDeactives($rendered, $actives);
		return $ret;
	}

	private function resolveActiveGOIds(Game $game) {
		$actives = collect(GameObject::activesOfGame($game)->get())->pluck('id')->toArray();
		// remove the root game object id from the list
		$actives = array_values(array_diff($actives, [$game->gameObject->id]));
		return $actives;
	}

	private function resolveDeactives(array $rendered, array $renderedVersions, array $actives): array
	{
		$deactives = [];
		if (empty($rendered)) {
			return $deactives;
		}
		foreach ($rendered as $id) {
			if (!in_array($id, $actives)) {
				$deactives[] = $id;
			}
		}
		return $deactives;
	}

	private function resolveActiveGameObjectsViews(Game $game, array $rendered, array $renderedVersions): array
	{
		$gameObject = $game->gameObject;
		$gameObjects = GameObject::activesOfGame($game)->get();
		$views = [];
		foreach ($gameObjects as $gameObject) {
			$view = $gameObject->view();
			if (empty($view)) {
				continue;
			}
			// if the game object is already rendered, skip it
			if (in_array($gameObject->id, $rendered) && $gameObject->id != 8) {
				continue;
			}
			$views[$gameObject->id] = $view;
		}
		return $views;
	}

}
