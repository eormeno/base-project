<?php

// Path: src/app/Models/MtqMap.php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use Illuminate\Support\Carbon;
use App\States\Map\MapDisplaying;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqMap extends Model implements IStateModel
{
    use HasFactory;

    protected $fillable = [
        'state',
        'started_at',
    ];

    public function mtqGame(): BelongsTo
    {
        return $this->belongsTo(MtqGame::class);
    }

    public function tiles(): HasMany
    {
        return $this->hasMany(MtqTile::class);
    }

    #region IStateModel implementation
    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        return 'map';
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return MapDisplaying::StateClass();
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
        return $this->started_at;
    }

    public function setEnteredAt(Carbon|string|null $startedAt): void
    {
        $this->update(['started_at' => $startedAt]);
    }
    #endregion

}
