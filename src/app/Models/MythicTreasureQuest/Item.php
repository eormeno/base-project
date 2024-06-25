<?php

namespace App\Models\MythicTreasureQuest;

use JsonSerializable;
use App\FSM\IStateManagedModel;

class Item implements JsonSerializable//, IStateManagedModel
{
    public const INFINITE = -1;
    private int $id;
    private string $name;
    private int $quantity;

    public function jsonSerialize(): array
    {
        return [
        ];
    }
}
