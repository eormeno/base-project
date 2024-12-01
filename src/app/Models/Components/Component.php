<?php

namespace App\Models\Components;

use App\Traits\DebugHelper;
use App\Models\GameObject\GameObject;
use App\Utils\ReflectionUtils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Component extends Model
{
    use DebugHelper;

    public $timestamps = false;
    protected $fillable = ['type', 'game_object_id', 'enabled', 'awoke', 'state'];
    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function gameObject(): BelongsTo
    {
        return $this->belongsTo(GameObject::class);
    }

    public function super(): BelongsTo
    {
        return $this->belongsTo(Component::class, 'id');
    }

    protected function findComponent(string $slug_type): Component
    {
        $type = ReflectionUtils::componentClass($slug_type);
        $game_object = $this->super->gameObject;
        return $game_object->components()->first([
            'type' => $type,
        ])->first()->subclass();
    }

    public function subclass() : Component
    {
        return $this->type::find($this->id);
    }

    public function onAwake(): void
    {
    }

    public function onStart(): void
    {
    }

    public function onUpdate(float $delta): void
    {
    }
}
