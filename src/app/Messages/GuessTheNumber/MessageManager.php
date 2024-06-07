<?php

namespace App\Messages\GuessTheNumber;

use App\Abstracts\Manager;
use App\Messages\GuessTheNumber\SuccessMessages;

class MessageManager extends Manager
{
    public function __construct()
    {
        $this->messageComponents = [
            // new SuccessMessages(),
            // new ShowingClueMessages(),
            // new GameOverMessages(),
        ];
    }

}
