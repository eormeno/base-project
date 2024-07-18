<?php

// Path: src/app/Models/MtqTile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MtqGameItem extends Model
{
    use HasFactory;

    public function mtqInventory() : BelongsTo
    {
        return $this->belongsTo(MtqInventory::class);
    }

    public function mtqItemClass() : BelongsTo
    {
        return $this->belongsTo(MtqItemClass::class);
    }
}
