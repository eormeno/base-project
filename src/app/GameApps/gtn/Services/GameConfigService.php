<?php

namespace App\GameApps\gtn\Services;

use App\Models\GameService;
use JsonSerializable;

class GameConfigService extends GameService implements JsonSerializable
{
    protected $table = null;
    const MIN_NUMBER = 1;
    const MAX_NUMBER = 1024;
    const CHEAT_NUMBER = 55555;

    public function getMinNumberAttribute(): int
    {
        return self::MIN_NUMBER;
    }

    public function getMaxNumberAttribute(): int
    {
        return self::MAX_NUMBER;
    }

    public function getMaxAttemtsAttribute(): int
    {
        return ceil(log($this->max_number - $this->min_number, 2));
    }

    public function getHalfAttemptsAttribute(): int
    {
        return ceil($this->max_attemts * 0.5);
    }

    public function getCheatNumberAttribute(): int
    {
        return self::CHEAT_NUMBER;
    }

    public function jsonSerialize(): array
    {
        return [
            'min_number' => $this->min_number,
            'max_number' => $this->max_number,
            'max_attempts' => $this->max_attemts,
            'half_attempts' => $this->half_attempts,
            'cheat_number' => $this->cheat_number,
        ];
    }
}
