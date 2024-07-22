<?php

// Path: src/app/Models/MtqInventory.php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use Illuminate\Database\Eloquent\Model;
use App\States\Inventory\InventoryDisplaying;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqInventory extends Model implements IStateModel
{
    use HasFactory;

    public function mtqGame() : BelongsTo
    {
        return $this->belongsTo(MtqGame::class);
    }

    public function mtqGameItems(): HasMany
    {
        return $this->hasMany(MtqGameItem::class);
    }

    #region IStateModel implementation
    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        return 'inventory';
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return InventoryDisplaying::StateClass();
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function updateState(?string $state): void
    {
        $this->state = $state;
    }
    #endregion
}
