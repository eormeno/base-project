<?php

namespace Database\Factories;

use App\Models\MtqMap;
use App\Helpers\MapHelper2;
use App\Models\MtqGameItem;
use App\Models\MtqInventory;
use App\Models\MtqItemClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class MtqInventoryFactory extends Factory
{
    public function definition()
    {
        return [
            'state' => null,
            'entered_at' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (MtqInventory $inventory) {
            $items = [];
            MtqItemClass::all()->each(function (MtqItemClass $itemClass) use ($inventory, &$items) {
                $items[] = MtqGameItem::factory()->quantity($itemClass->default_quantity)->for($inventory)->for($itemClass)->create();
            });
            $inventory->mtqGameItems()->saveMany($items);
        });

    }

}
