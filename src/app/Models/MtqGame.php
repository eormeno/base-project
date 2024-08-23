<?php

// Path: src/app/Models/MtqGame.php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use App\States\MythicTreasureQuest\Initial;
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

    public static function getInitialStateClass(): ReflectionClass
    {
        return Initial::StateClass();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): IStateModel | null
    {
        return null;
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
