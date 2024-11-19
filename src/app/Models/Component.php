<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Component extends Model
{
    public $timestamps = false;
    protected $fillable = ['type', 'game_object_id', 'active'];
    protected $casts = [
        'active' => 'boolean',
    ];

    public function gameObject(): BelongsTo
    {
        return $this->belongsTo(GameObject::class);
    }

    public function subclass()
    {
        return $this->type::find($this->id);
    }

    public function onStart(): void
    {
    }

    public function onUpdate(float $delta): void
    {
    }

    public function onStateChange(string $oldState, string $newState): void
    {
    }
}
