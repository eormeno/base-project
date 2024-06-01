<?php

namespace App\Exceptions\GuessTheNumber;

class FailException extends NumberException
{
    public function __construct(string $message, ?int $number = null)
    {
        parent::__construct($message, $number);
    }
}
