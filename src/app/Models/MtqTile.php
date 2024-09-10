<?php

// Path: src/app/Models/MtqTile.php

namespace App\Models;

use ReflectionClass;
use App\States\Tile\Hidden;
use App\States\Tile\Revealed;
use App\States\Tile\FlaggedTile;
use App\States\Tile\FlaggingTile;
use App\States\Tile\GameOverTile;
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

    public static function states(): array
    {
        return [
            Hidden::class,
            FlaggedTile::class,
            FlaggingTile::class,
            GameOverTile::class,
            Revealed::class,
        ];
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
