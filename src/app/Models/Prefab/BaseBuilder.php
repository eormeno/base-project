<?php

namespace App\Models\Prefab;

use App\Models\Game;
use Illuminate\Support\Facades\DB;
use App\Models\GameObject\GameObject;

abstract class BaseBuilder extends Base
{
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
		foreach ($children as $child_name => $child_data) {
			if ($child_name === 'states' || $child_name === 'components') {
				continue;
			}
			if (!$this->createChildFromPrefab($game, $parent, $child_name, $child_data)) {
				$this->createChild($game, $parent, $child_name, $child_data);
			}
		}
	}

	private function createChildFromPrefab(Game $game, GameObject $parent, string $child_name, array $child_data): bool
	{
		if ($child_prefab_name = $child_data['prefab'] ?? null) {
			if ($child_prefab = Prefab::findPrefab($child_prefab_name)) {
				$active = $child_data['active'] ?? true;
				$child_attributes = $child_data['attributes'] ?? [];
				$child_prefab->createPrefabStructure($game, $parent, $child_name, $active, $child_attributes);
				return true;
			}
		}
		return false;
	}

	private function createChild(Game $game, GameObject $parent, string $childName, array $childData): void
	{
		$child = GameObject::create([
			'name' => $childName,
			'active' => $childData['active'] ?? true,
			'game_object_id' => $parent->id,
			'game_id' => $game->id
		]);
		foreach ($childData as $key => $value) {
			if ($key === 'states') {
				$this->createStateComponents($child, $value);
				continue;
			}
			if ($key === 'components') {
				$this->createComponents($child, $value);
				continue;
			}
			if (!$this->createChildFromPrefab($game, $child, $key, $value)) {
				$this->createChild($game, $child, $key, $value);
			}
		}
	}
}
