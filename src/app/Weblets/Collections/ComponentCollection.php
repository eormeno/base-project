<?php

namespace App\Weblets\Collections;

use Iterator;
use App\Weblets\Component;

class ComponentCollection implements Iterator {
    private $components = [];
    private $position = 0;

    public function __construct() {
        $this->position = 0;
    }

    public function addComponent(Component $component) {
        $this->components[] = $component;
    }

    public function getComponent($index) {
        return $this->components[$index] ?? null;
    }

    // Iterator methods
    public function current() : Component {
        return $this->components[$this->position];
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
        return isset($this->components[$this->position]);
    }
}
