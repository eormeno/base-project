<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    use HasFactory;
    protected $fillable = ['invitation_code', 'game_app_id', 'game_object_id'];

    public function gameApp(): BelongsTo
    {
        return $this->belongsTo(GameApp::class);
    }

    public function gameObject(): HasOne
    {
        return $this->hasOne(GameObject::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
