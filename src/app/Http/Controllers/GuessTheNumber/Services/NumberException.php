<?php

namespace App\Http\Controllers\GuessTheNumber\Services;

abstract class NumberException extends \Exception
{
    private $number;
    public function __construct($number)
    {
        parent::__construct();
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
    }
}
