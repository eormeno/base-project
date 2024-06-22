<?php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateManagedModel;
use Illuminate\Database\Eloquent\Model;
use App\States\MythicTreasureQuest\Initial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MythicTreasureQuestGame extends Model implements IStateManagedModel
{
    use HasFactory;

    protected $fillable = [
        'state',
    ];

    public function getId(): int
    {
        return $this->id;
    }

    protected $casts = [
        'map' => 'array',
        'inventory' => 'array',
    ];

    public static function getInitialStateClass(): ReflectionClass
    {
        return Initial::StateClass();
    }

    public function getState(): string|null
    {
        return $this->state;
    }

    public function updateState(string|null $state): void
    {
        $this->update(['state' => $state]);
    }

    public function __get($key): mixed
    {
        return $this->getAttribute($key);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
