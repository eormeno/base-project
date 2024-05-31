<?php

namespace App\Http\Controllers\GuessTheNumber\Exceptions;

class NotInRangeException extends NumberException
{
    private ?int $min;
    private ?int $max;

    public function __construct(string $message, ?int $number = null, ?int $min = null, ?int $max = null)
    {
        parent::__construct($message, $number);
        $this->min = $min;
        $this->max = $max;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }
}
