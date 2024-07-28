<?php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\States\MythicTreasureQuest\Initial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MythicTreasureQuestGame extends Model implements IStateModel
{
    use HasFactory;

    protected $fillable = [
        'state',
        'entered_at',
    ];

    protected $casts = [
        'map' => 'array',
        'inventory' => 'array',
    ];

    #region IStateModel implementation
    public function getId(): int
    {
        return $this->id;
    }

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

    public function getEnteredAt(): string|null
    {
        return $this->entered_at;
    }

    public function setEnteredAt(Carbon|string|null $enteredAt): void
    {
        $this->update(['entered_at' => $enteredAt]);
    }
    #endregion

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
