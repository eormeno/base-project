<?php

namespace App\Http\Controllers\GuessTheNumber\Exceptions;

class FailException extends NumberException
{
    public function __construct(string $message, ?int $number = null)
    {
        parent::__construct($message, $number);
    }
}
