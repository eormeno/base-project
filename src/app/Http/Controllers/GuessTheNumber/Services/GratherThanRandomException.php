<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

class GratherThanRandomException extends NumberException
{
    public function __construct($number)
    {
        parent::__construct($number);
    }
}
