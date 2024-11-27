<?php

namespace App\Models\Components;

use App\Models\GameObject;
use App\Services\MessageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Component extends Model
{
    public $timestamps = false;
    protected $fillable = ['type', 'game_object_id', 'enabled', 'awoke'];
    protected $casts = [
        'enabled' => 'boolean',
    ];
    protected $messageService;

    public function setMessageServiceAttribute(MessageService $messageService): void
    {
        $this->messageService = $messageService;
    }

    public function gameObject(): BelongsTo
    {
        return $this->belongsTo(GameObject::class);
    }

    public function subclass()
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
