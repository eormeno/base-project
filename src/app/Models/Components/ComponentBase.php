<?php

namespace App\Models\Components;

use App\Models\GameObject\GameObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentBase extends Model
{
    public $timestamps = false;
    protected $fillable = ['type', 'game_object_id', 'enabled', 'awoke', 'state', 'messages'];
    protected $casts = [
        'enabled' => 'boolean',
        'awoke' => 'boolean',
        'messages' => 'array',
    ];

    protected static function enablingState(): string|null
    {
        return null;
    }

    public function gameObject(): BelongsTo
    {
        return $this->belongsTo(GameObject::class);
    }

    public function super(): BelongsTo
    {
        return $this->belongsTo(Component::class, 'id');
    }

    public function subclass(): Component
    {
        return $this->type::find($this->id);
    }
}
