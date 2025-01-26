<?php

namespace App\Models\Prefab;

use App\Models\Game;
use InvalidArgumentException;
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
		if (empty($children)) {
			return;
		}
		foreach ($children as $childName => $childConfig) {
			if ($childName === 'states' || $childName === 'components') {
				continue;
			}
			//$parsedChildName = $this->parseChildName($childName);
			if (!$this->createChildFromPrefab($game, $parent, $childName, $childConfig)) {
				$this->createChild($game, $parent, $childName, $childConfig);
			}
		}
	}

	private function parseChildName(string $childName): array
	{
		// Trim whitespace and validate the input
		$childName = trim($childName);
		if (empty($childName)) {
			throw new InvalidArgumentException('Child name cannot be empty.');
		}

		$parts = explode(':', $childName);

		// Validate the number of parts
		if (count($parts) > 2) {
			throw new InvalidArgumentException('Child name format is invalid.');
		}

		// also check for both parts to be non-empty
		if (empty($parts[0]) || (count($parts) === 2 && empty($parts[1]))) {
			throw new InvalidArgumentException('Child name format is invalid.');
		}

		// Handle single part names
		if (count($parts) === 1) {
			return [
				'is_prefab' => false,
				'name' => $parts[0]
			];
		}

		// Handle two part names
		return [
			'is_prefab' => true,
			'name' => $parts[0],
			'prefab' => $parts[1]
		];
	}

	private function createChildFromPrefab(Game $game, GameObject $parent, string $childName, array $childConfig): bool
	{
		echo "Creating child from prefab: $parent $childName\n";
		if ($child_prefab_name = $childConfig['prefab'] ?? null) {
			if ($child_prefab = Prefab::findPrefab($child_prefab_name)) {
				$active = $childConfig['active'] ?? true;
				$child_attributes = $childConfig['attributes'] ?? [];
				$child_prefab->createPrefabStructure($game, $parent, $childName, $active, $child_attributes);
				return true;
			}
		}
		return false;
	}

	private function createChild(Game $game, GameObject $parent, string $childName, array $childConfig): void
	{
		$child = GameObject::create([
			'name' => $childName,
			'active' => $childConfig['active'] ?? true,
			'game_object_id' => $parent->id,
			'game_id' => $game->id
		]);
		foreach ($childConfig as $key => $value) {
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
