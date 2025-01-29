<?php

namespace App\Services;

use App\Models\Game;
use App\Events\FrontEvent;
use App\Traits\DebugHelper;
use App\Contracts\IRenderer;

class RendererService implements IRenderer
{
	use DebugHelper;

	public function render(Game $game, array $eventInfo): array
	{
		$this->log('RendererService::render(' . json_encode($eventInfo) . ')');
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
		$ret = [
			'elapsed' => 0,
			'root' => $rootGameObject->id,
		];
		$views = $this->resolveActiveGameObjectsViews($game, $jsonClient);
		foreach ($views as $id => $view) {
			$ret[$id] = $view;
		}
		$ret['actives'] = collect($game->activeGameObjects())->pluck('id')->toArray();
		return $ret;
	}

	private function resolveActiveGameObjectsViews(Game $game, bool $jsonClient): array
	{
		$gameObject = $game->gameObject;
		$gameObjects = $game->activeGameObjects();
		$views = [];
		foreach ($gameObjects as $gameObject) {
			$view = $gameObject->view();
			if (empty($view)) {
				continue;
			}
			$views[$gameObject->id] = $view;
		}
		return $views;
	}

}
