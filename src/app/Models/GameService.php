<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameService extends Model
{
    public $timestamps = false;
    protected $fillable = ['slug', 'type', 'game_id'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function subclass() : GameService
    {
        return $this->type::find($this->id);
    }
}
