<?php

namespace App\GameApps\gtn\Services;

use App\Models\GameService;

class GameConfigService extends GameService
{
    protected $table = null;
    const MIN_NUMBER = 1;
    const MAX_NUMBER = 1024;
    const CHEAT_NUMBER = 55555;

    private function min_number(): int
    {
        return self::MIN_NUMBER;
    }

    private function max_number(): int
    {
        return self::MAX_NUMBER;
    }

    private function max_attemts(): int
    {
        return ceil(log($this->max_number() - $this->min_number(), 2));
    }

    private function half_attempts(): int
    {
        return ceil($this->max_attemts() * 0.5);
    }

    private function cheat_number(): int
    {
        return self::CHEAT_NUMBER;
    }
}
