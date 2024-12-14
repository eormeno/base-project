<?php

namespace App\GameApps\Components;

use App\Models\Components\PersistentComponent;

class Transform2DComponent extends PersistentComponent
{
    public static function config(): array
    {
        return [
            'x' => ['integer', 0],
            'y' => ['integer', 0],
            'rotation' => ['integer', 0],
            'scale' => ['float', 1.0],
        ];
    }
}
