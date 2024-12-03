<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameService extends Model
{
    public $timestamps = false;
    protected $fillable = ['type', 'game_app_id'];

    public function gameApp(): BelongsTo
    {
        return $this->belongsTo(GameApp::class);
    }

    public function subclass() : GameService
    {
        return $this->type::find($this->id);
    }
}
