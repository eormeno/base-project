<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameObject extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'is_active', 'transform', 'parent_id'];

    public function components(): MorphMany
    {
        return $this->morphMany(Component::class, 'componentable');
    }

    public function getComponent($componentType)
    {
        $componentModel = "App\\Models\\" . Str::studly_case($componentType) . "Component";
        return $this->morphOne($componentModel, 'componentable')->first();
    }

    public function addComponent($componentType, array $properties = [])
    {
        $componentModel = "App\\Models\\" . Str::studly_case($componentType) . "Component";
        return $this->morphOne($componentModel, 'componentable')->create($properties);
    }
}
