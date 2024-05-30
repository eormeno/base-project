<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

class LowerThanRandomException extends NumberException
{
    public function __construct($number)
    {
        parent::__construct($number);
    }
}
