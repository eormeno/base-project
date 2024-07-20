<?php

// Path: src/app/Models/MtqMap.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqMap extends Model
{
    use HasFactory;

    public function mtqGame(): BelongsTo
    {
        return $this->belongsTo(MtqGame::class);
    }

    public function tiles(): HasMany
    {
        return $this->hasMany(MtqTile::class);
    }
}
