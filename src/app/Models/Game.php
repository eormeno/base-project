<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Game extends Model
{
    use HasFactory;
    protected $fillable = ['invitation_code', 'game_app_id', 'game_object_id'];

    public function gameApp() : BelongsTo
    {
        return $this->belongsTo(GameApp::class);
    }

    public function rootGameObject(): HasOne
    {
        return $this->hasOne(GameObject::class, 'id', 'game_object_id');
    }
}
