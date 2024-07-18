<?php

// Path: src/app/Models/MtqInventory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class MtqInventory extends Model
{

    public function mtqGame()
    {
        return $this->belongsTo(MtqGame::class);
    }

    public function mtqGameItems(): HasMany
    {
        return $this->hasMany(MtqGameItem::class);
    }
}
