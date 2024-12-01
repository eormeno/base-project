<?php

namespace App\Services\GTN;

use App\Contracts\IServiceProvider;

class GameConfigService implements IServiceProvider
{
    const MIN_NUMBER = 1;
    const MAX_NUMBER = 1024;
    const CHEAT_NUMBER = 55555;

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

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
