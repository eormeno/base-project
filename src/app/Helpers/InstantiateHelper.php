<?php

namespace App\Helpers;

use App\Models\Game;
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
        $gameObject->componentsIterator(function (Component $component, Component $subclass) {
            $subclass->onAwake();
            $component->update(['awoke' => true]);
        });
        return $gameObject;
    }

    protected static function createGameObjectHierarchy(
        ?GameObject $parent = null,
        Prefab $prefab
    ): GameObject {

        $gameObject = GameObject::create([
            'name' => $prefab->name,  // the first object's name is the prefab's name
        ]);

        $components = $prefab->structure['components'] ?? [];
        foreach ($components as $slug_type => $attributes) {
            self::createComponent($gameObject, $slug_type, $attributes);
        }

        $children = $prefab->structure['children'] ?? [];
        foreach ($children as $name => $childData) {
            self::createChildFromStructure($gameObject, $name, $childData);
        }

        return $gameObject;
    }

    protected static function createChildFromStructure(
        GameObject $parent,
        string $name,
        array $childData
    ): GameObject {
        $child = GameObject::create([
            'name' => $childData['name'],
            'active' => $childData['active'] ?? true,
            'game_object_id' => $parent->id
        ]);
        foreach ($childData['components'] as $slug_type => $attributes) {
            self::createComponent($child, $slug_type, $attributes);
        }
        if (isset($childData['children'])) {
            foreach ($childData['children'] as $grandChildData) {
                self::createChildFromStructure($child, $grandChildData);
            }
        }
        return $child;
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
