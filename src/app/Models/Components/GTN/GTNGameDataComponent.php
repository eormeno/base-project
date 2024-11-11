<?php

namespace App\Models\Components\GTN;

use App\Models\Components\Component;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GTNGameDataComponent extends Component
{
    use HasFactory;
    protected $fillable = ['times_played', 'half_attempts', 'score', 'max_attempts', 'min_number', 'max_number'];
}
