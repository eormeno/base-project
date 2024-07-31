<?php

// Path: src/app/Models/MtqInventory.php

namespace App\Models;

use ReflectionClass;
use App\States\Inventory\InventoryDisplaying;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqInventory extends AStateModel
{
    use HasFactory;
    protected $fillable = [
        'state',
        'entered_at',
        'children',
        'attributes',
    ];

    public function mtqGame() : BelongsTo
    {
        return $this->belongsTo(MtqGame::class);
    }

    public function mtqGameItems(): HasMany
    {
        return $this->hasMany(MtqGameItem::class);
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return InventoryDisplaying::StateClass();
    }
}
