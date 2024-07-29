<?php

// Path: src/app/Models/MtqTile.php

namespace App\Models;

use ReflectionClass;
use App\States\Item\ItemNormalState;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqGameItem extends AStateModel
{
    use HasFactory;

    protected $fillable = [
        'state',
        'entered_at',
        'state_children',
        'state_attributes',
    ];

    public function mtqInventory(): BelongsTo
    {
        return $this->belongsTo(MtqInventory::class);
    }

    public function mtqItemClass(): BelongsTo
    {
        return $this->belongsTo(MtqItemClass::class);
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return ItemNormalState::StateClass();
    }
}
