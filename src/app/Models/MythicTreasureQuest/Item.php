<?php

namespace App\Models\MythicTreasureQuest;

use JsonSerializable;

class Item implements JsonSerializable
{
    private int $id;
    private int $item_id;
    private int $quantity;

    public function __construct(int $id, int $item_id, int $quantity)
    {
        $this->id = $id;
        $this->item_id = $item_id;
        $this->quantity = $quantity;
    }

    public static function fromJson(array $data): Item
    {
        $id = $data['id'];
        $item_id = $data['item_id'];
        $quantity = $data['quantity'];
        return new Item($id, $item_id, $quantity);
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'quantity' => $this->quantity
        ];
    }
}
