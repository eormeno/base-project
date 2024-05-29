<?php

namespace App\Http\Controllers\GuessTheNumber;

class Globals
{
    const MIN_NUMBER = 1;
    const MAX_NUMBER = 1024;
    const CHEAT_NUMBER = 55555;

    /**
     * Calculate the remaining attempts based on the log in 2 base of the difference between
     * max and min number.
     *
     * @return int
     */
    public static function maxAttempts(): int
    {
        return ceil(log(self::MAX_NUMBER - self::MIN_NUMBER, 2));
    }

    public static function halfAttempts(): int
    {
        return ceil(self::maxAttempts() * 0.5);
    }
}
