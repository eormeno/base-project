<?php

namespace App\Models\MythicTreasureQuest;

use JsonSerializable;

class Bag implements JsonSerializable
{
    private array $items = [];
    private ?Item $selected = null;

    public static function fromJson(array $data): Bag
    {
        $items = $data['items'];
        $map = new Bag();
        foreach ($items as $item) {
            $map->addItem(Item::fromJson($item));
        }
        return $map;
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItem(int $index): Item
    {
        return $this->items[$index];
    }

    public function jsonSerialize(): array
    {
        return [
            'items' => $this->items
        ];
    }
}
