<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

class CheatException extends \Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
