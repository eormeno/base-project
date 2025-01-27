<?php

namespace App\Models\Prefab;

use App\Models\Game;
use Illuminate\Support\Facades\DB;
use App\Models\GameObject\GameObject;
use App\Models\Prefab\Parsers\GameObjectNameParser;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class BaseBuilder extends Base
{
	private function getChildNameParser(): GameObjectNameParser
	{
		try {
			return app()->make(GameObjectNameParser::class);
		} catch (BindingResolutionException $e) {
			return new GameObjectNameParser();
		}
	}

	final public function buildGameObject(Game $game, bool $active = true, array $attributes = []): GameObject
	{
		$gameObject = DB::transaction(function () use ($game, $active, $attributes) {
			return $this->createPrefabStructure(
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

	private function createPrefabStructure(
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
		$this->createStateComponents($gameObject, $this->structure()['states'] ?? []);
		$this->createComponents($gameObject, $this->structure()['components'] ?? []);
		$this->createChildren($game, $gameObject, $this->structure());
		$this->afterInstantiate($gameObject, $initParams);
		return $gameObject;
	}

	private function createComponents(GameObject $gameObject, array $components): void
	{
		foreach ($components as $slug_type => $attributes) {
			$gameObject->addComponent($slug_type, $attributes);
		}
	}

	private function createStateComponents(GameObject $gameObject, array $states): void
	{
		$state_components = [];
		$initial_state = array_key_first($states) ?? null;
		foreach ($states as $state => $component_config) {
			$enabled = $state === $initial_state;
			$component_slug = array_key_first($component_config);
			$component_attributes = $component_config[$component_slug];
			$component_attributes['enabled'] = $enabled;
			$component = $gameObject->addComponent($component_slug, $component_attributes);
			$state_components[$state] = $component->id;
		}
		$gameObject->update(['state' => $initial_state, 'state_components' => $state_components]);
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
			$result = $this->getChildNameParser()->parse($childName);
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
	): void {
		$foundChildPrefab = Prefab::findPrefab($childPrefab);
		$active = $childConfig['active'] ?? true;
		$prefabAttrs = $childConfig['attributes'] ?? [];
		$newChildGameObject = $foundChildPrefab->createPrefabStructure(
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
				$this->createStateComponents($newChildGameObject, $grandChildConfig);
				continue;
			}
			if ($grandChildName === 'components') {
				$this->createComponents($newChildGameObject, $grandChildConfig);
				continue;
			}
			$result = $this->getChildNameParser()->parse($grandChildName);
			if ($result->isPrefab) {
				$this->createChildFromPrefab($game, $newChildGameObject, $result->name, $result->prefab, $grandChildConfig);
				continue;
			}
			$this->createChild($game, $newChildGameObject, $result->name, $grandChildConfig);
		}
	}

	private function createChild(Game $game, GameObject $parent, string $childName, array $childConfig): void
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
				$this->createStateComponents($child, $grandChildConfig);
				continue;
			}
			if ($grandChildName === 'components') {
				$this->createComponents($child, $grandChildConfig);
				continue;
			}
			$result = $this->getChildNameParser()->parse($grandChildName);
			if ($result->isPrefab) {
				$this->createChildFromPrefab($game, $child, $result->name, $result->prefab, $grandChildConfig);
				continue;
			}
			$this->createChild($game, $child, $result->name, $grandChildConfig);
		}
	}
}
