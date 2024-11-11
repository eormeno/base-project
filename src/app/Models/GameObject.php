<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameObject extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'is_active', 'state', 'parent_id'];

    public function components(): MorphMany
    {
        return $this->morphMany(Component::class, 'componentable');
    }

    public function getComponent($componentType)
    {
        $componentModel = "App\\Models\\Components\\" . Str::studly($componentType) . "Component";
        return $this->morphOne($componentModel, 'componentable')->first();
    }

    public function addComponent($componentType, array $properties = [])
    {
        $componentModel = "App\\Models\\Components\\" . Str::studly($componentType) . "Component";
        return $this->morphOne($componentModel, 'componentable')->create($properties);
    }
}
