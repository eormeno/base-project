<?php

namespace App\Models\Components;

use App\Models\GameService;
use App\Traits\DebugHelper;
use App\Utils\ReflectionUtils;
use App\Models\GameObject\GameObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentBase extends Model
{
    use DebugHelper;

    public $timestamps = false;
    protected $fillable = ['type', 'game_object_id', 'enabled', 'awoke', 'state', 'messages'];
    protected $casts = [
        'enabled' => 'boolean',
        'awoke' => 'boolean',
        'messages' => 'array',
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

    public function subclass(): Component
    {
        return $this->type::find($this->id);
    }

    public function getService(string $slug_type): GameService
    {
        return $this->super->gameObject->game->getService($slug_type);
    }
}
