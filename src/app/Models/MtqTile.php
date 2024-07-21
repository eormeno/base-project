<?php

// Path: src/app/Models/MtqTile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtqTile extends Model
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
}
