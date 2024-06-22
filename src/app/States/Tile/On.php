<?php

namespace App\States\Tile;

use App\FSM\StateAbstractImpl;

class On extends StateAbstractImpl
{
    public string $hasTrap = '';

    public function onRefresh(): void
    {
        $this->hasTrap = $this->context->object->hasTrap ? 'T' : '';
    }

    public function onTileOnClickEvent()
    {
        return Off::StateClass();
    }
}
