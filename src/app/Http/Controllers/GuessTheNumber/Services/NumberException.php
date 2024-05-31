<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

abstract class NumberException extends \Exception
{
    private ?int $number;

    public function __construct(string $message, ?int $number = null)
    {
        parent::__construct($message);
        $this->number = $number;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }
}
