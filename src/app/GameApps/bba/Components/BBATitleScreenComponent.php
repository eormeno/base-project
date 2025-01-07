<?php

namespace App\GameApps\bba\Components;

use App\Models\Components\PersistentComponent;

class BBATitleScreenComponent extends PersistentComponent
{
    protected $table = 'bba_title_screen_components';

    public static function config(): array
    {
        return [];
    }
}
