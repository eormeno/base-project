<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Component extends Model
{
    public $timestamps = false;
    protected $fillable = ['component_type', 'game_object_id', 'active', 'properties'];
    protected $casts = [
        'properties' => 'array',
        'active' => 'boolean',
    ];

    public function gameObject(): BelongsTo
    {
        return $this->belongsTo(GameObject::class);
    }

    public function componentable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function onStart(): void
    {
    }

    protected function onUpdate(float $delta): void
    {
    }

    protected function onStateChange(string $oldState, string $newState): void
    {
    }
}
