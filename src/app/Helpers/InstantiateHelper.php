<?php

namespace App\Helpers;

use ReflectionClass;
use App\Models\Prefab;
use App\Utils\ReflectionUtils;
use App\Models\Components\IState;
use Illuminate\Support\Facades\DB;
use App\Models\Components\Component;
use App\Models\GameObject\GameObject;
use App\Models\GameObject\GameObjectBase;

class InstantiateHelper
{
    public static function instantiatePrefab(Prefab $prefab): GameObject
    {
        $gameObject = DB::transaction(function () use ($prefab) {
            return self::createGameObjectHierarchy(null, $prefab);
        });
        // TODO a esto hay que estudiarlo bien, porque no se si es necesario
        // $gameObject->componentsIterator(function (Component $component, Component $subclass) {
        //     $subclass->onAwake();
        //     $component->update(['awoke' => true]);
        // });
        return $gameObject;
    }

    protected static function createGameObjectHierarchy(
        ?GameObject $parent = null,
        Prefab $prefab,
        ?string $name = null,
        bool $active = true
    ): GameObject {
        $gameObject = GameObject::create([
            'name' => $name ?? $prefab->name,
            'active' => $active,
        ]);
        $components = $prefab->structure['components'] ?? [];
        foreach ($components as $slug_type => $attributes) {
            self::createComponent($gameObject, $slug_type, $attributes);
        }
        $children = $prefab->structure['children'] ?? [];
        foreach ($children as $childName => $childData) {
            if (isset($childData['prefab'])) {
                $childPrefab = Prefab::where('name', $childData['prefab'])->first();
                if ($childPrefab) {
                    $active = $childData['active'] ?? true;
                    self::createGameObjectHierarchy($gameObject, $childPrefab, $childName, $active);
                    continue;
                }
            }
            self::createChildren($gameObject, $childName, $childData);
        }
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
            self::createComponent($child, $slug_type, $attributes);
        }
        $grandChildren = $childData['children'] ?? [];
        foreach ($grandChildren as $grandChildName => $grandChildData) {
            self::createChildren($child, $grandChildName, $grandChildData);
        }
    }

    protected static function createComponent(
        GameObjectBase $gameObject,
        string $slug_type,
        array $attributes
    ): Component {
        $type = ReflectionUtils::componentClass($slug_type);
        $component = $gameObject->components()->create(self::stateComponentConfig($type));
        return $type::create(array_merge(['id' => $component->id], $attributes));
    }

    protected static function stateComponentConfig($type): array
    {
        $attributes = ['type' => $type, 'enabled' => true];
        $class = new ReflectionClass($type);
        if ($class->implementsInterface(IState::class)) {
            return array_merge($attributes, ['enabled' => false, 'state' => $type::state()]);
        }
        return $attributes;
    }
}
