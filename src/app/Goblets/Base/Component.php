<?php

namespace App\Goblets\Base;

class Component {

    protected bool $enabled = true;

    public function __get($name)
    {
        return $this->$name;
    }

}
