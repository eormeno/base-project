<?php

namespace App\Models;

use App\Models\Component;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameObject extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'active', 'state', 'parent_id'];

    /**
     * Mutator for the 'name' attribute that slugifies it.
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = Str::slug($value);
    }

    public function components(): MorphMany
    {
        return $this->morphMany(Component::class, 'componentable');
    }

    public function groups() : HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function getComponent($componentType)
    {
        $componentModel = "App\\Models\\Components\\" . Str::studly($componentType) . "Component";
        return $this->morphOne($componentModel, 'componentable')->first();
    }

    public function addComponent($componentType, array $properties = [])
    {
        $componentModel = "App\\Models\\Components\\" . Str::studly($componentType) . "Component";
        $newComponent = $this->morphOne($componentModel, 'componentable')->create($properties);
        $newComponent->gameObject()->associate($this);
        $newComponent->save();
        return $newComponent;
    }
}
