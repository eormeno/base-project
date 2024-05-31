<?php

namespace App\Http\Controllers\GuessTheNumber\Exceptions;

class SuccessException extends \Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
