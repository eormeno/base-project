<?php

namespace App\Services\GuessTheNumber;

use App\Services\AbstractStateManager;

class GuessTheNumberStateManager extends AbstractStateManager
{
    public function __construct()
    {
        $this->serviceManager = new GuessTheNumberGameServiceManager();
    }
}
