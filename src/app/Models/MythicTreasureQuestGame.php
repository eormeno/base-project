<?php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use Illuminate\Database\Eloquent\Model;
use App\States\MythicTreasureQuest\Initial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MythicTreasureQuestGame extends Model implements IStateModel
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

    public function getAlias(): string
    {
        return 'main';
    }

    public function getState(): string|null
    {
        return $this->state;
    }

    public function updateState(string|null $state): void
    {
        $this->update(['state' => $state]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
