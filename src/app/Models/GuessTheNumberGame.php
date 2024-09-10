<?php
namespace App\Models;

use App\States\GuessTheNumber\Initial;
use App\States\GuessTheNumber\Playing;
use App\States\GuessTheNumber\Success;
use App\States\GuessTheNumber\GameOver;
use App\States\GuessTheNumber\Preparing;
use App\States\GuessTheNumber\ShowingClue;
use App\States\GuessTheNumber\AskingToPlay;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuessTheNumberGame extends AStateModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state',
        'entered_at',
        'children',
        'view',
        'min_number',
        'max_number',
        'max_attempts',
        'half_attempts',
        'remaining_attempts',
    ];

    public static function states(): array
    {
        return [
            Initial::class,
            Preparing::class,
            Playing::class,
            GameOver::class,
            AskingToPlay::class,
            Success::class,
            ShowingClue::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
