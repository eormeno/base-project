<?php

namespace App\GameApps\Common\Components;

use App\Models\Components\PersistentComponent;

class ButtonComponent extends PersistentComponent
{
    public static function config(): array
    {
        return [
            'event' => ['string', ''],
            'data' => ['json', null],
            'text' => ['string', ''],
            'style' => ['string', ''],
        ];
    }
}
