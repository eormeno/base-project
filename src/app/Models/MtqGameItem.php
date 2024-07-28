<?php

// Path: src/app/Models/MtqTile.php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use Illuminate\Support\Carbon;
use App\States\Item\ItemNormalState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqGameItem extends Model implements IStateModel
{
    use HasFactory;

    protected $fillable = [
        'state',
        'entered_at',
    ];

    public function mtqInventory(): BelongsTo
    {
        return $this->belongsTo(MtqInventory::class);
    }

    public function mtqItemClass(): BelongsTo
    {
        return $this->belongsTo(MtqItemClass::class);
    }

    #region IStateManagedModel
    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        return "item{$this->id}";
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return ItemNormalState::StateClass();
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
        return $this->entered_at;
    }

    public function setEnteredAt(Carbon|string|null $enteredAt): void
    {
        $this->update(['entered_at' => $enteredAt]);
    }
    #endregion
}
