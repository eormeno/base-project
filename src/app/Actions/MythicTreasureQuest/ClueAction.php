<?php

namespace App\Actions\MythicTreasureQuest;

use App\FSM\IStateManagedModel;
use App\FSM\StateAbstractImpl;
use App\Traits\ToastTrigger;

class ClueAction
{
    use ToastTrigger;

    public function use($inventoryRepository): void
    {
        $this->infoToast('Clue shown!');
        $inventoryRepository->decrementItemBySlug('clue');
    }
}
