<?php

namespace App\GameApps\Common\Components;

use App\Models\Components\PersistentComponent;


class UiContainerComponent extends PersistentComponent
{
    public static function config(): array
    {
        return [
            'components' => ['json', null],
            'children' => ['json', null],
        ];
    }
}
