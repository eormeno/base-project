<?php

namespace App\Models;

use App\Services\MessageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Component extends Model
{
    public $timestamps = false;
    protected $fillable = ['type', 'game_object_id', 'active', 'awoke'];
    protected $casts = [
        'active' => 'boolean',
    ];
    protected $messageService;

    // a setter for message service
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

    public function awake(): void
    {
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
