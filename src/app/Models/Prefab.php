<?php

namespace App\Models;

use App\Helpers\InstantiateHelper;
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

    public final function instantiate(array $attributes = []): GameObject
    {
        $gameObject = InstantiateHelper::instantiatePrefab($this);
        $gameObject = $this->afterInstantiated($gameObject, $attributes);
        return $gameObject;
    }

    protected function afterInstantiated(GameObject $gameObject, array $attributes = []): GameObject
    {
        return $gameObject;
    }
}
