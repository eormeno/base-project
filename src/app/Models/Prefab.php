<?php

namespace App\Models;

use Str;
use App\Models\Component;
use App\Services\MessageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\OutputStyle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prefab extends Model
{
    use HasFactory;

    protected $keyType = 'string'; // PK es un string

    public $incrementing = false; // PK no es autoincremental

    protected $primaryKey = 'name'; // PK es 'name'

    public $timestamps = false;

    protected $fillable = ['name', 'structure'];

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

    public function instantiate(): GameObject
    {
        $gameObject = DB::transaction(function () {
            return $this->createGameObjectHierarchy(null);
        });
        // iterate all the game object's components and execute the 'awake' method and set the 'awoke' attribute to true
        $gameObject->components->each(function (Component $component) {
            $subclass = $component->type::find($component->id);
            if (!$subclass) {
                throw new \Exception("Component subclass not found for component with id {$component->id} and type {$component->type}");
            }
            $messageService = app(MessageService::class);
            $subclass->setMessageServiceAttribute($messageService);
            $subclass->awake();
            $component->update(['awoke' => true]);
        });
        return $gameObject;
    }

    protected function createGameObjectHierarchy(?GameObject $parent = null): GameObject
    {
        // Crear el GameObject raíz del prefab
        $gameObject = GameObject::create([
            'name' => $this->structure['name'],
            'state' => $this->structure['state'],
        ]);

        // Crear los componentes definidos en la estructura
        foreach ($this->structure['components'] as $slug_type => $attributes) {
            $this->createComponent($gameObject, $slug_type, $attributes);
        }

        // Crear los hijos recursivamente
        if (isset($this->structure['children'])) {
            foreach ($this->structure['children'] as $childData) {
                $this->createChildFromStructure($gameObject, $childData);
            }
        }

        return $gameObject;
    }

    protected function createChildFromStructure(GameObject $parent, array $childData): GameObject
    {
        $child = GameObject::create([
            'name' => $childData['name'],
            'prefab_id' => $this->id,
            'parent_id' => $parent->id
        ]);

        foreach ($childData['components'] as $slug_type => $attributes) {
            $this->createComponent($child, $slug_type, $attributes);
        }

        if (isset($childData['children'])) {
            foreach ($childData['children'] as $grandChildData) {
                $this->createChildFromStructure($child, $grandChildData);
            }
        }

        return $child;
    }

    protected function createComponent(GameObject $gameObject, string $slug_type, array $attributes): Component
    {
        return $gameObject->addComponent($slug_type, $attributes);
    }
}
