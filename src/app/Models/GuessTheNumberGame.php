<?php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use Illuminate\Support\Carbon;
use App\States\GuessTheNumber\Initial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuessTheNumberGame extends Model implements IStateModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state',
        'started_at',
        'min_number',
        'max_number',
        'max_attempts',
        'half_attempts',
        'remaining_attempts',
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
        return $this->started_at;
    }

    public function setEnteredAt(Carbon|string|null $startedAt): void
    {
        $this->update(['started_at' => $startedAt]);
    }
    #endregion

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
