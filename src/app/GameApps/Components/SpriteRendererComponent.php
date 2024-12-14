<?php

namespace App\GameApps\Components;

use App\Models\Components\PersistentComponent;

class SpriteRendererComponent extends PersistentComponent
{
    public static function config(): array
    {
        return [
            'texture' => ['string', ''],
            'layer' => ['integer', 0],
        ];
    }
}
