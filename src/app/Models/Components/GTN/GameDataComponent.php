<?php

namespace App\Models\Components\GTN;

use App\Models\Component;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameDataComponent extends Component
{
    protected $table = 'gtn_game_data_components';
    protected $fillable = ['id', 'score', 'max_attempts', 'min_number', 'max_number', 'attempts', 'random_number'];

    public function super() : BelongsTo
    {
        return $this->belongsTo(Component::class, 'id', 'id');
    }
}
