<?php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use App\States\GuessTheNumber\Initial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuessTheNumberGame extends AStateModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state',
        'entered_at',
        'children',
        'view',
        'min_number',
        'max_number',
        'max_attempts',
        'half_attempts',
        'remaining_attempts',
    ];

    public static function getInitialStateClass(): ReflectionClass
    {
        return Initial::StateClass();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): IStateModel|null
    {
        return null;
    }
}
