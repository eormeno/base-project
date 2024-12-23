<?php

namespace App\Models;

use App\Helpers\InstantiateHelper;
use Illuminate\Support\Facades\DB;
use App\Models\GameObject\GameObject;
use Illuminate\Database\Eloquent\Model;

class Prefab extends Model
{
    protected $keyType = 'string';  // PK is string
    public $incrementing = false;   // PK is not autoincrement
    protected $primaryKey = 'name'; // PK is 'name'
    public $timestamps = false;

    protected $fillable = ['name', 'type', 'structure'];

    protected $casts = [
        'structure' => 'array',
    ];

    public static function structure(): array
    {
        return [];
    }

    public function afterInstantiate(GameObject $gameObject, array $attributes = []): void
    {
        echo "Prefab::afterInstantiate\n";
    }

    public final function instantiate(bool $active = true, array $attributes = []): GameObject
    {
        $gameObject = DB::transaction(function () use ($active, $attributes) {
            return InstantiateHelper::createPrefabStructure(
                parent: null,
                prefab: $this,
                name: null,
                active: $active,
                prefab_attributes: $attributes
            );
        });
        // TODO a esto hay que estudiarlo bien, porque no se si es necesario
        // $gameObject->componentsIterator(function (Component $component, Component $subclass) {
        //     $subclass->onAwake();
        //     $component->update(['awoke' => true]);
        // });
        return $gameObject;
        //return InstantiateHelper::instantiatePrefab($this);
    }

    public static function findPrefab(string $name): ?Prefab
    {
        $prefab = self::where('name', $name)->first();
        if (!$prefab) {
            return null;
        }
        $type = $prefab->type;
        return new $type($prefab->toArray());
    }
}
