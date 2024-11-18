<?php

namespace App\Models;

use Str;
use Illuminate\Support\Facades\DB;
use App\Models\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prefab extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'description', 'structure'];

    protected $casts = [
        'structure' => 'array',
    ];

    /**
     * Mutator that slugifies the 'name' attribute.
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = Str::slug($value);
    }

    public function instantiate(
        array $place = ['slot' => 'main']
    ): GameObject {
        return DB::transaction(function () use ($place) {
            return $this->createGameObjectHierarchy(null, $place);
        });
    }

    protected function createGameObjectHierarchy(
        ?GameObject $parent = null,
        array $place = ['slot' => 'main']
    ): GameObject {
        // Crear el GameObject raíz del prefab
        $gameObject = GameObject::create([
            'name' => $this->structure['name'],
            'state' => $this->structure['state'],
        ]);

        // Crear los componentes definidos en la estructura
        foreach ($this->structure['components'] as $componentData) {
            $this->createComponent($gameObject, $componentData);
        }

        // // Si hay un TransformComponent, establecer la posición
        // if ($transform = $gameObject->getComponent('transform')) {
        //     $transform->update(['position' => $place]);
        // }

        // // Crear los hijos recursivamente
        // if (isset($this->structure['children'])) {
        //     foreach ($this->structure['children'] as $childData) {
        //         $this->createChildFromStructure($gameObject, $childData);
        //     }
        // }

        return $gameObject;
    }

    protected function createChildFromStructure(
        GameObject $parent,
        array $childData
    ): GameObject {
        $child = GameObject::create([
            'name' => $childData['name'],
            'prefab_id' => $this->id,
            'parent_id' => $parent->id
        ]);

        foreach ($childData['components'] as $componentData) {
            $this->createComponent($child, $componentData);
        }

        if (isset($childData['children'])) {
            foreach ($childData['children'] as $grandChildData) {
                $this->createChildFromStructure($child, $grandChildData);
            }
        }

        return $child;
    }

    protected function createComponent(GameObject $gameObject, array $componentData): Component
    {
        return $gameObject->addComponent($componentData['type'], $componentData['properties']);
    }
}
