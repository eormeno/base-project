<?php

namespace App\Actions\MythicTreasureQuest;

use App\Traits\ToastTrigger;

class ClueAction
{
    use ToastTrigger;

    public function use(): void
    {
        $this->infoToast('Do you want to consume one clue?');
    }
}
