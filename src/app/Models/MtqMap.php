<?php

// Path: src/app/Models/MtqMap.php

namespace App\Models;

use ReflectionClass;
use App\States\Map\MapDisplaying;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqMap extends AStateModel
{
    use HasFactory;

    protected $fillable = [
        'state',
        'entered_at',
        'children',
        'view',
    ];

    public function mtqGame(): BelongsTo
    {
        return $this->belongsTo(MtqGame::class);
    }

    public function tiles(): HasMany
    {
        return $this->hasMany(MtqTile::class);
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return MapDisplaying::StateClass();
    }

}
