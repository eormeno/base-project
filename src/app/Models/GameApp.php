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
        'description',
        'min_age',
        'image',
        'prefab_name',
        'client',
        'width',
        'height',
        'version',
        'max_instances_per_user',
        'min_players_per_instance',
        'max_players_per_instance',
        'active',
        'service_registry',
    ];

    protected $casts = [
        'active' => 'boolean',
        'service_registry' => 'array',
    ];

    public function games() : HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function prefab(): HasOne {
        return $this->hasOne(Prefab::class, 'name', 'prefab_name');
    }
}
