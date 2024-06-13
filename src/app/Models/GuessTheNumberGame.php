<?php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateManagedModel;
use App\States\GuessTheNumber\Initial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuessTheNumberGame extends Model implements IStateManagedModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state',
        'min_number',
        'max_number',
        'max_attempts',
        'half_attempts',
        'remaining_attempts',
    ];

    public function getId(): int
    {
        return $this->id;
    }

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
