<?php

namespace App\Services\GuessTheNumber;

class GameConfigService
{
    const MIN_NUMBER = 1;
    const MAX_NUMBER = 1024;
    const CHEAT_NUMBER = 55555;

    public function getMinNumber(): int
    {
        return self::MIN_NUMBER;
    }

    public function getMaxNumber(): int
    {
        return self::MAX_NUMBER;
    }

    /**
     * Calculate the remaining attempts based on the log in 2 base of the difference between
     * max and min number.
     *
     * @return int
     */
    public function getMaxAttempts(): int
    {
        return ceil(log($this->getMaxNumber() - $this->getMinNumber(), 2));
    }

    public function getHalfAttempts(): int
    {
        return ceil($this->getMaxAttempts() * 0.5);
    }

    public function getCheatNumber(): int
    {
        return self::CHEAT_NUMBER;
    }
}
