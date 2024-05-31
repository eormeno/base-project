<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

class SuccessException extends \Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
