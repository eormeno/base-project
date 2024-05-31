<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

class GameOverException extends \Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
