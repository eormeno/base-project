<?php

namespace App\Http\Controllers\GuessTheNumber\Exceptions;

class GameOverException extends \Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
