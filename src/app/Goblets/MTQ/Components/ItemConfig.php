<?php

namespace App\Goblets\MTQ\Components;

use App\Goblets\Base\Component;

class ItemConfig extends Component
{
    public $slug;
    public $icon;
    public $name;
    public $quantity;

    public function __construct($slug, $icon, $name, $quantity)
    {
        $this->slug = $slug;
        $this->icon = $icon;
        $this->name = $name;
        $this->quantity = $quantity;
    }

    public function doAction()
    {
        return $this->name;
    }
}
