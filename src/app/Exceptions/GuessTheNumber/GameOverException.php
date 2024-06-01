<?php

namespace App\Exceptions\GuessTheNumber;

class GameOverException extends \Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
