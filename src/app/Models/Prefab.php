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

	/**
	 * Returns the states and the component associated to each one, for the game object that will be instantiated
	 * from this prefab.
	 * The key is the state name and the value is an array whose key is the slug name of the component and its value
	 * is the initilization attributes. The first element of the array is the initial state.
	 * @return array
	 */
	public static function states(): array
	{
		return [];
	}

    public static function structure(): array
    {
        return [];
    }

	/**
	 * Returns the children prefabs that will be instantiated as children of the game object that will be instantiated
	 * from this prefab. The key is the name of the child and the value is an array with the following keys:
	 * - prefab: the name of the prefab to instantiate.
	 * - active: a boolean indicating if the child is active or not.
	 * - attributes: an array with the attributes to initialize the child.
	 * @return array
	 */
	public static function children(): array
	{
		return [];
	}

	/**
	 * Returns the components that will be instantiated in the game object that will be instantiated from this prefab.
	 * The key is the slug name of the component and the value is an array with the attributes to initialize the
	 * component.
	 * @return array
	 */
	public static function components(): array
	{
		return [];
	}

    public function afterInstantiate(GameObject $gameObject, array $attributes = []): void
    {
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
