<?php

namespace App\Weblets\Collections;

use Iterator;
use App\Weblets\Attribute;

class AttributeCollection implements Iterator {
    private $attributes = [];
    private $position = 0;

    public function __construct() {
        $this->position = 0;
    }

    public function addAttribute(Attribute $attribute) {
        $this->attributes[] = $attribute;
    }

    public function getAttribute($index) {
        return $this->attributes[$index] ?? null;
    }

    // Iterator methods
    public function current() : Attribute {
        return $this->attributes[$this->position];
    }

    public function key() : int {
        return $this->position;
    }

    public function next() : void {
        ++$this->position;
    }

    public function rewind() : void {
        $this->position = 0;
    }

    public function valid() : bool {
        return isset($this->attributes[$this->position]);
    }
}
