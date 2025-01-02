<?php

namespace App\Helpers;

use App\Models\Prefab;
use App\Models\GameObject\GameObject;

class InstantiateHelper
{
    public static function createPrefabStructure(
        ?GameObject $parent = null,
        Prefab $prefab,
        ?string $name = null,
        bool $active = true,
        array $prefab_attributes = []
    ): GameObject {
        $prefab = Prefab::findPrefab($prefab->name);
        $gameObject = GameObject::create(['name' => $name ?? $prefab->name, 'active' => $active]);
		$state_components = $prefab->structure['states'] ?? [];
        $components = $prefab->structure['components'] ?? [];
        foreach ($components as $slug_type => $attributes) {
            $gameObject->addComponent($slug_type, $attributes);
        }
        $children = $prefab->structure['children'] ?? [];
        foreach ($children as $child_name => $child_data) {
            if ($child_prefab_name = $child_data['prefab'] ?? null) {
                if ($child_prefab = Prefab::findPrefab($child_prefab_name)) {
                    $active = $child_data['active'] ?? true;
                    $child_attributes = $child_data['attributes'] ?? [];
                    self::createPrefabStructure($gameObject, $child_prefab, $child_name, $active, $child_attributes);
                    continue;
                }
            }
            self::createChildren($gameObject, $child_name, $child_data);
        }
        $prefab->afterInstantiate(gameObject: $gameObject, attributes: $prefab_attributes);
        return $gameObject;
    }

    protected static function createChildren(GameObject $parent, string $childName, array $childData): void
    {
        $child = GameObject::create([
            'name' => $childName,
            'active' => $childData['active'] ?? true,
            'game_object_id' => $parent->id
        ]);
        $components = $childData['components'] ?? [];
        foreach ($components as $slug_type => $attributes) {
            $child->addComponent($slug_type, $attributes);
        }
        $grandChildren = $childData['children'] ?? [];
        foreach ($grandChildren as $grandChildName => $grandChildData) {
            self::createChildren($child, $grandChildName, $grandChildData);
        }
    }
}
