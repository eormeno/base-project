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
            ->reject(fn ($id) => $id === $rootId)
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









// sugerencia Qwen

// namespace App\Services;

// use App\Models\Game;
// use App\Events\FrontEvent;
// use App\Traits\DebugHelper;
// use App\Contracts\IRenderer;
// use App\Models\GameObject\GameObject;

// class RendererService implements IRenderer
// {
//     use DebugHelper;

//     public function render(Game $game, array $eventInfo): array
//     {
//         $currentTimestamp = microtime(true);
//         event(new FrontEvent($game, $eventInfo));

//         $result = $this->request($game, $eventInfo);

//         $elapsed = ceil((microtime(true) - $currentTimestamp) * 1000);
//         $result['elapsed'] = $elapsed;

//         return $result;
//     }

//     private function request(Game $game, array $event): array
//     {
//         $jsonClient = $game->gameApp->client === 'webgl';
//         $rootGameObject = $game->gameObject;
//         $rendered = $event['rendered'];

//         // Obtener los objetos activos una sola vez
//         $activeGameObjects = GameObject::activesOfGame($game)->get();
//         $activeIds = $this->resolveActiveGOIds($activeGameObjects, $rootGameObject->id);

//         $ret = [
//             'elapsed' => 0,
//         ];

//         if (!$jsonClient) {
//             $ret['root'] = $rootGameObject->id;
//         }

//         // Resolver vistas de objetos activos
//         $views = $this->resolveActiveGameObjectsViews($activeGameObjects, $rendered);
//         foreach ($views as $id => $view) {
//             $ret[$id] = $view;
//         }

//         // Resolver objetos desactivados
//         $deactives = $this->resolveDeactives($rendered, $activeIds);
//         if (!empty($deactives)) {
//             $ret['deactives'] = $deactives;
//         }

//         return $ret;
//     }

//     private function resolveActiveGOIds($activeGameObjects, $rootId): array
//     {
//         return collect($activeGameObjects)
//             ->pluck('id')
//             ->reject(fn ($id) => $id === $rootId)
//             ->values()
//             ->toArray();
//     }

//     private function resolveDeactives(array $rendered, array $actives): array
//     {
//         if (empty($rendered)) {
//             return [];
//         }

//         return array_keys(array_diff_key($rendered, array_flip($actives)));
//     }

//     private function resolveActiveGameObjectsViews($activeGameObjects, array $rendered): array
//     {
//         $views = [];

//         foreach ($activeGameObjects as $gameObject) {
//             $view = $gameObject->view();

//             if (empty($view)) {
//                 continue;
//             }

//             // Saltar si el objeto ya está renderizado con la misma versión
//             if (isset($rendered[$gameObject->id]) && $rendered[$gameObject->id] === $gameObject->version) {
//                 continue;
//             }

//             $views[$gameObject->id] = $view;
//         }

//         return $views;
//     }
// }


// Optimización sugerida por Deepseek

// namespace App\Services;

// use App\Models\Game;
// use App\Events\FrontEvent;
// use App\Contracts\IRenderer;
// use App\Models\GameObject\GameObject;
// use Illuminate\Database\Eloquent\Collection;

// class RendererService implements IRenderer
// {
// 	public function render(Game $game, array $eventInfo): array
// 	{
// 		event(new FrontEvent($game, $eventInfo));

// 		$startTime = microtime(true);
// 		$result = $this->request($game, $eventInfo);

// 		$result['elapsed'] = ceil((microtime(true) - $startTime) * 1000);

// 		return $result;
// 	}

// 	private function request(Game $game, array $event): array
// 	{
// 		$jsonClient = $game->gameApp->client == 'webgl';
// 		$ret = [
// 			'elapsed' => 0,
// 		];

// 		if (!$jsonClient) {
// 			$ret['root'] = $game->gameObject->id;
// 		}

// 		// Optimización: Single query para objetos activos
// 		$activeGameObjects = GameObject::activesOfGame($game)->get();

// 		// Procesar vistas optimizado
// 		$views = $this->resolveActiveGameObjectsViews($event['rendered'], $activeGameObjects);
// 		$ret = array_merge($ret, $views);

// 		// Procesar desactivados optimizado
// 		$actives = $this->resolveActiveGOIds($game, $activeGameObjects);
// 		$deactives = $this->resolveDeactives($event['rendered'], $actives);

// 		if (!empty($deactives)) {
// 			$ret['deactives'] = $deactives;
// 		}

// 		return $ret;
// 	}

// 	private function resolveActiveGOIds(Game $game, Collection $activeGameObjects): array
// 	{
// 		return $activeGameObjects
// 			->pluck('id')
// 			->reject(fn($id) => $id === $game->gameObject->id)
// 			->values()
// 			->toArray();
// 	}

// 	private function resolveDeactives(array $rendered, array $actives): array
// 	{
// 		if (empty($rendered))
// 			return [];

// 		$activeLookup = array_flip($actives);

// 		return collect($rendered)
// 			->keys()
// 			->reject(fn($id) => isset($activeLookup[$id]))
// 			->map(fn($id) => (string) $id)
// 			->toArray();
// 	}

// 	private function resolveActiveGameObjectsViews(array $rendered, Collection $activeGameObjects): array
// 	{
// 		return $activeGameObjects
// 			->filter(function (GameObject $gameObject) use ($rendered) {
// 				// Filtramos primero para evitar llamadas a view() innecesarias
// 				if (empty($gameObject->view()))
// 					return false;

// 				return !isset($rendered[$gameObject->id])
// 					|| $rendered[$gameObject->id] != $gameObject->version;
// 			})
// 			->mapWithKeys(function (GameObject $gameObject) {
// 				return [$gameObject->id => $gameObject->view()];
// 			})
// 			->toArray();
// 	}
// }
