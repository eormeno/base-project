<?php

namespace App\Goblets\MTQ\Components;

use App\Goblets\Base\Component;

class InventoryConfig extends Component
{
    public $selected_item = null;
    public $items = [];

    public function __construct()
    {
    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function removeItem($item)
    {
        $index = array_search($item, $this->items);
        if ($index !== false) {
            unset($this->items[$index]);
        }
    }

    public function selectItem($item)
    {
        $this->selected_item = $item;
    }

}
