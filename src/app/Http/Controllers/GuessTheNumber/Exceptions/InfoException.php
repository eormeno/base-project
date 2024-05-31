<?php

namespace App\Http\Controllers\GuessTheNumber\Exceptions;

class InfoException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
