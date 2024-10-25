<?php

namespace App\Weblets\Collections;

use ArrayIterator;
use App\Weblets\Weblet;

class WebletsCollection extends ArrayIterator
{
    public function __construct(array $weblets = [])
    {
        parent::__construct($weblets);
    }

    public function append($weblet)
    {
        if (!$weblet instanceof Weblet) {
            throw new \InvalidArgumentException('Only Weblet objects can be added to the collection.');
        }
        parent::append($weblet);
    }

    public function current() : array
    {   return parent::current();
    }

    public function offsetGet($index): Weblet
    {
        return parent::offsetGet($index);
    }
}
