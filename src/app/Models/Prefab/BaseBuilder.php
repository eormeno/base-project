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
		// TODO a esto hay que estudiarlo bien, porque no se si es necesario
		// $gameObject->componentsIterator(function (Component $component, Component $subclass) {
		//     $subclass->onAwake();
		//     $component->update(['awoke' => true]);
		// });
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
		$this->createStateComponents($gameObject);
		$this->createComponents($gameObject);
		// $components = $this->structure['components'] ?? [];
		// foreach ($components as $slug_type => $attributes) {
		// 	$gameObject->addComponent($slug_type, $attributes);
		// }
		$this->createChildren($game, $gameObject);
		$this->afterInstantiate(gameObject: $gameObject, attributes: $initParams);
		return $gameObject;
	}

	private function createComponents(GameObject $gameObject): void
	{
		$components = $this->components();
		foreach ($components as $slug_type => $attributes) {
			$gameObject->addComponent($slug_type, $attributes);
		}
	}

	private function createStateComponents(GameObject $gameObject): void
	{
		$states = $this->states();
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

	private function createChildren(Game $game, GameObject $parent): void
	{
		$children = $this->children();
		foreach ($children as $child_name => $child_data) {
			if ($child_prefab_name = $child_data['prefab'] ?? null) {
				if ($child_prefab = Prefab::findPrefab($child_prefab_name)) {
					$active = $child_data['active'] ?? true;
					$child_attributes = $child_data['attributes'] ?? [];
					$child_prefab->createPrefabStructure($game, $parent, $child_name, $active, $child_attributes);
					continue;
				}
			}
			$this->createChild($game, $parent, $child_name, $child_data);
		}
	}

	private function createChild(Game $game, GameObject $parent, string $childName, array $childData): void
	{
		$child = GameObject::create([
			'name' => $childName,
			'active' => $childData['active'] ?? true,
			'game_object_id' => $parent->id,
			'game_id' => $game->id
		]);
		$components = $childData['components'] ?? [];
		foreach ($components as $slug_type => $attributes) {
			$child->addComponent($slug_type, $attributes);
		}
		$grandChildren = $childData['children'] ?? [];
		foreach ($grandChildren as $grandChildName => $grandChildData) {
			$this->createChild($game, $child, $grandChildName, $grandChildData);
		}
	}
}
