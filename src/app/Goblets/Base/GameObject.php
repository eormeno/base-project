<?php

namespace App\Goblets\Base;

class GameObject {

    private $components = [];

    public function addComponent(Component $component) {
        $this->components[] = $component;
    }

}
