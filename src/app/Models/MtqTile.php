<?php

// Path: src/app/Models/MtqTile.php

namespace App\Models;

use ReflectionClass;
use App\States\Tile\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqTile extends AStateModel
{
    use HasFactory;

    protected $fillable = [
        'x',
        'y',
        'state',
        'entered_at',
        'children',
        'view',
        'has_trap',
        'has_flag',
        'marked_as_clue',
        'traps_around',
    ];

    public function mtqMap(): BelongsTo
    {
        return $this->belongsTo(MtqMap::class);
    }

    public function isRevealed(): bool
    {
        return $this->state === 'revealed';
    }

    public static function getInitialStateClass(): ReflectionClass
    {
        return Hidden::StateClass();
    }
}
