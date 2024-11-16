<?php

namespace App\Models\Components;

use App\Models\GameObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class Component extends Model
{
    public $timestamps = false;
    protected $fillable = ['component_type', 'game_object_id', 'active', 'properties'];
    protected $casts = [
        'properties' => 'array'
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
