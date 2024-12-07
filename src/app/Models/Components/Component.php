<?php

namespace App\Models\Components;

use App\Models\GameService;
use App\Traits\DebugHelper;
use Illuminate\Support\Str;
use App\Utils\ReflectionUtils;
use App\Models\GameObject\GameObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Component extends Model
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

    protected function updateView($key, $value = null): array
    {
        $messages = $this->super->messages;
        if ($messages === null) {
            $messages = [];
        }
        if (!is_array($key)) {
            $key = [$key => $value];
        }
        $messages = array_merge($messages, $key);
        // for each value in $key, apply i18n
        foreach ($messages as $k => $v) {
            // if k ends with _array, then v is an array of keys
            if (Str::endsWith($k, '_array')) {
                continue;
            }
            $messages[$k] = __("guess-the-number.$k", $v);
        }
        $this->super->messages = $messages;
        $this->super->save();
        $this->log(json_encode($messages, JSON_PRETTY_PRINT));
        return $messages;
    }

    protected function messages(): array
    {
        return $this->super->messages;
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
