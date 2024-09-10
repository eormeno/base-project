<?php

// Path: src/app/Models/MtqGame.php

namespace App\Models;

use ReflectionClass;
use App\States\MythicTreasureQuest\Initial;
use App\States\MythicTreasureQuest\Playing;
use App\States\MythicTreasureQuest\Flagging;
use App\States\MythicTreasureQuest\GameOver;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqGame extends AStateModel
{
    use HasFactory;

    protected $fillable = [
        'state',
        'entered_at',
        'children',
        'view',
    ];

    public static function states(): array
    {
        return [
            Initial::class,
            Playing::class,
            Flagging::class,
            GameOver::class,
        ];
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return Initial::StateClass();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getWidthAttribute(): int
    {
        return $this->mtqMaps->width;
    }

    public function getHeightAttribute(): int
    {
        return $this->mtqMaps->height;
    }

    public function mtqMaps(): HasOne
    {
        return $this->hasOne(MtqMap::class);
    }

    public function mtqInventories(): HasOne
    {
        return $this->hasOne(MtqInventory::class);
    }
}
