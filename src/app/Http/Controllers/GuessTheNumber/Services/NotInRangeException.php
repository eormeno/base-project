<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

class NotInRangeException extends NumberException
{
    private $min;
    private $max;

    public function __construct($number, $min, $max)
    {
        parent::__construct($number);
        $this->min = $min;
        $this->max = $max;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function getMax()
    {
        return $this->max;
    }
}
