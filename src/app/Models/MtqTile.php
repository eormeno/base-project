<?php

// Path: src/app/Models/MtqTile.php

namespace App\Models;

use ReflectionClass;
use App\FSM\IStateModel;
use App\States\Tile\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqTile extends Model implements IStateModel
{
    use HasFactory;

    protected $fillable = [
        'x',
        'y',
        'state',
        'has_trap',
        'has_flag',
        'marked_as_clue',
        'traps_around',
    ];

    public function mtqMap(): BelongsTo
    {
        return $this->belongsTo(MtqMap::class);
    }

    #region IStateModel implementation
    public function getId(): int
    {
        return $this->id;
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return Hidden::StateClass();
    }

    public function getAlias(): string
    {
        return "tile{$this->id}";
    }

    public function getState(): string|null
    {
        return $this->state;
    }

    public function updateState(string|null $state): void
    {
        $this->update(['state' => $state]);
    }
    #endregion
}
