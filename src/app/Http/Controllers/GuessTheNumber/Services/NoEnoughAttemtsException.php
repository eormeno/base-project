<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

class NoEnoughAttemtsException extends \Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
