<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'active', 'game_object_id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(GameObject::class);
    }

    public function gameObjects() : HasMany
    {
        return $this->hasMany(GameObject::class);
    }
}
