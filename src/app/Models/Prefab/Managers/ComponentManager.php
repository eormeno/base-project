<?php

namespace App\Models\Prefab\Managers;

use App\Models\GameObject\GameObject;

class ComponentManager
{

	public function awakeComponents(GameObject $gameObject, array $initParams): void
	{
		$gameObject->components->each(function ($component) use ($initParams) {
			$component->subclass()->onAwake($initParams);
		});
	}

	public function createComponents(GameObject $gameObject, array $components): void
	{
		foreach ($components as $slug_type => $attributes) {
			$gameObject->addComponent($slug_type, $attributes);
		}
	}

	public function createStateComponents(GameObject $gameObject, array $states): void
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
}
