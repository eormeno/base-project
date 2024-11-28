<?php

namespace App\Models;

use App\Helpers\InstantiateHelper;
use App\Models\GameObject\GameObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prefab extends Model
{
    use HasFactory;

    protected $keyType = 'string';  // PK is string
    public $incrementing = false;   // PK is not autoincrement
    protected $primaryKey = 'name'; // PK is 'name'
    public $timestamps = false;

    protected $fillable = ['name', 'structure'];

    protected $casts = [
        'structure' => 'array',
    ];

    public function instantiate(): GameObject
    {
        return InstantiateHelper::instantiatePrefab($this);
    }
}
