<?php

namespace App\Services\GuessTheNumber;

use App\Services\AbstractServiceComponent;

class ClueService extends AbstractServiceComponent
{
    public function getClues(): array
    {
        $ret = [];
        $number = $this->gameService->getRandomNumber();

        $minIterations = $this->getMinimalIterations($number);
        $ret[] = ['clue' => 'iterations', 'data' => $minIterations];

        if ($this->isPrime($number)) {
            $ret[] = ['clue' => 'prime', 'data' => 'is-prime'];
            return $ret;
        }

        // if ($this->isEven($number)) {
        //     $ret[] = ['clue' => 'even', 'data' => 'is-even'];
        // } else {
        //     $ret[] = ['clue' => 'odd', 'data' => 'is-odd'];
        // }

        //$multiples = $this->getMultiples($number);
        $factors = $this->getPrimeFactors($number);
        $ret[] = ['clue' => 'multiples', 'data' => $factors];

        return $ret;
    }

    public function findRandomNumber(): int
    {
        $min = $this->gameConfigService->getMinNumber();
        $max = $this->gameConfigService->getMaxNumber();
        do {
            $number = random_int($min, $max);
        } while ($this->getMinimalIterations($number) > 6);
        return $number;
    }

    public function getMinimalIterations(int $number): int
    {
        $min = $this->gameConfigService->getMinNumber();
        $max = $this->gameConfigService->getMaxNumber();
        return $this->binarySearchIterations($min, $max, $number);
    }

    private function binarySearchIterations(int $min, int $max, int $number): int
    {
        $start = $min;
        $end = $max;
        $iterations = 0;
        while ($start <= $end) {
            $iterations++;
            $middle = intdiv($start + $end, 2);
            if ($number == $middle) {
                return $iterations;
            } elseif ($number < $middle) {
                $end = $middle - 1;
            } else {
                $start = $middle + 1;
            }
        }
        return $iterations;
    }

    private function isPrime(int $number): bool
    {
        if ($number <= 1) {
            return false;
        }
        for ($i = 2; $i <= sqrt($number); $i++) {
            if ($number % $i == 0) {
                return false;
            }
        }
        return true;
    }

    private function isEven(int $number): bool
    {
        return $number % 2 == 0;
    }

    public function getPrimeFactors(int $number): array
    {
        $multiples = [];
        for ($i = 2; $i < $number; $i++) {
            if ($number % $i == 0) {
                if ($this->isPrime($i)) {
                    $multiples[] = $i;
                }
            }
        }
        return $multiples;
    }

}
