<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameApp extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'prefix',
        'name',
        'image',
        'description',
        'min_age',
        'prefab',
        'active',
        'version',
        'max_instances',
        'min_players_per_instance',
        'max_players_per_instance',
    ];

    public function games() : HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function prefab(): HasOne {
        return $this->hasOne(Prefab::class, 'name', 'prefab');
    }
}
