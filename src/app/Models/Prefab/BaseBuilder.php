<?php

namespace App\Models\Prefab;

use App\Models\Game;
use App\Models\Prefab\Managers\ComponentManager;
use Illuminate\Support\Facades\DB;
use App\Models\GameObject\GameObject;
use App\Models\Prefab\Parsers\GameObjectNameParser;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class BaseBuilder extends Base
{
	private function gameObjectNameParser(): GameObjectNameParser
	{
		try {
			return app()->make(GameObjectNameParser::class);
		} catch (BindingResolutionException $e) {
			return new GameObjectNameParser();
		}
	}

	private function componentManager(): ComponentManager
	{
		try {
			return app()->make(ComponentManager::class);
		} catch (BindingResolutionException $e) {
			return new ComponentManager();
		}
	}

	final public function buildGameObject(Game $game, bool $active = true, array $attributes = []): GameObject
	{
		$gameObject = DB::transaction(function () use ($game, $active, $attributes) {
			return $this->buildGameObjectFromPrefab(
				game: $game,
				parent: null,
				gameObjectName: null,
				active: $active,
				initParams: $attributes
			);
		});
		// TODO Hay que ver si se ejecuta el awake de los componentes...
		return $gameObject;
	}

	private function buildGameObjectFromPrefab(
		Game $game,
		?GameObject $parent = null,
		?string $gameObjectName = null,	// new game object optional name
		bool $active = true,
		array $initParams = []
	): GameObject {
		$gameObject = GameObject::create(
			[
				'name' => $gameObjectName ?? $this->name,
				'active' => $active,
				'game_object_id' => $parent?->id,
				'game_id' => $game->id
			]
		);
		$this->componentManager()->createStateComponents($gameObject, $this->structure()['states'] ?? []);
		$this->componentManager()->createComponents($gameObject, $this->structure()['components'] ?? []);
		$this->createChildren($game, $gameObject, $this->structure());
		//$this->componentManager()->awakeComponents($gameObject, $initParams);
		return $gameObject;
	}

	private function createChildren(Game $game, GameObject $parent, array $children): void
	{
		if (empty($children)) {
			return;
		}
		foreach ($children as $childName => $childConfig) {
			if (
				$childName === 'states' ||
				$childName === 'components' || $childName === 'active' || $childName === 'attributes'
			) {
				continue;
			}
			$result = $this->gameObjectNameParser()->parse($childName);
			if ($result->isPrefab) {
				$this->createChildFromPrefab($game, $parent, $result->name, $result->prefab, $childConfig);
				continue;
			}
			$this->createChild($game, $parent, $result->name, $childConfig);
		}
	}

	private function createChildFromPrefab(
		Game $game,
		GameObject $parent,
		string $childName,
		string $childPrefab,
		array $childConfig
	): GameObject {
		$foundChildPrefab = Prefab::findPrefab($childPrefab);
		$active = $childConfig['active'] ?? true;
		$prefabAttrs = $childConfig['attributes'] ?? [];
		$child = $foundChildPrefab->buildGameObjectFromPrefab(
			$game,
			$parent,
			$childName,
			$active,
			$prefabAttrs
		);
		foreach ($childConfig as $grandChildName => $grandChildConfig) {
			if ($grandChildName === 'active' || $grandChildName === 'attributes') {
				continue;
			}
			if ($grandChildName === 'states') {
				$this->componentManager()->createStateComponents($child, $grandChildConfig);
				continue;
			}
			if ($grandChildName === 'components') {
				$this->componentManager()->createComponents($child, $grandChildConfig);
				continue;
			}
			$result = $this->gameObjectNameParser()->parse($grandChildName);
			if ($result->isPrefab) {
				$this->createChildFromPrefab($game, $child, $result->name, $result->prefab, $grandChildConfig);
				continue;
			}
			$this->createChild($game, $child, $result->name, $grandChildConfig);
		}
		$this->componentManager()->awakeComponents($child, $prefabAttrs);
		return $child;
	}

	private function createChild(Game $game, GameObject $parent, string $childName, array $childConfig): GameObject
	{
		$child = GameObject::create([
			'name' => $childName,
			'active' => $childConfig['active'] ?? true,
			'game_object_id' => $parent->id,
			'game_id' => $game->id
		]);
		foreach ($childConfig as $grandChildName => $grandChildConfig) {
			if ($grandChildName === 'active' || $grandChildName === 'attributes') {
				continue;
			}
			if ($grandChildName === 'states') {
				$this->componentManager()->createStateComponents($child, $grandChildConfig);
				continue;
			}
			if ($grandChildName === 'components') {
				$this->componentManager()->createComponents($child, $grandChildConfig);
				continue;
			}
			$result = $this->gameObjectNameParser()->parse($grandChildName);
			if ($result->isPrefab) {
				$this->createChildFromPrefab($game, $child, $result->name, $result->prefab, $grandChildConfig);
				continue;
			}
			$this->createChild($game, $child, $result->name, $grandChildConfig);
		}
		$this->componentManager()->awakeComponents($child, $childConfig['attributes'] ?? []);
		return $child;
	}
}
