<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameService extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'slug', 'type', 'game_id'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function subclass() : GameService
    {
        return $this->type::find($this->id);
    }

    public function getService(string $slug): GameService
    {
        return $this->game->getService($slug);
    }
}
