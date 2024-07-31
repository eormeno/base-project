<?php

namespace App\Models;

use ReflectionClass;
use App\States\MythicTreasureQuest\Initial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MythicTreasureQuestGame extends AStateModel
{
    use HasFactory;

    protected $fillable = [
        'state',
        'entered_at',
        'children',
        'view',
    ];

    protected $casts = [
        'map' => 'array',
        'inventory' => 'array',
    ];

    public static function getInitialStateClass(): ReflectionClass
    {
        return Initial::StateClass();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
