<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;

class Hidden extends StateAbstractImpl
{
    public bool $hasTrap = false;
    public int $trapsAround = 0;

    public function onRefresh(): void
    {
        $this->hasTrap = $this->model->hasTrap;
        $this->trapsAround = $this->model->trapsAround;
    }

    public function onRevealEvent()
    {
        return Revealed::StateClass();
    }

    public function onTileOffClickEvent()
    {
        $this->sendEvent('reveal');
    }
}
