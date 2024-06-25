<?php

namespace App\Models\MythicTreasureQuest;

use JsonSerializable;
use App\FSM\IStateManagedModel;

class Item implements JsonSerializable//, IStateManagedModel
{
    public function jsonSerialize(): array
    {
        return [
        ];
    }
}
