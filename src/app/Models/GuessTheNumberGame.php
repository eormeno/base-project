<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuessTheNumberGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'min_number',
        'max_number',
        'max_attempts',
        'half_attempts',
        'remaining_attempts',
    ];

    protected $casts = [
        'finished' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
