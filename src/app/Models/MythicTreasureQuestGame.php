<?php

namespace App\Models;

use App\FSM\IStateManagedModel;
use Illuminate\Database\Eloquent\Model;
use App\States\MythicTreasureQuest\Initial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MythicTreasureQuestGame extends Model implements IStateManagedModel
{
    use HasFactory;

    public function getId(): int
    {
        return $this->id;
    }

    public static function getInitialStateClass(): string
    {
        return Initial::class;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
