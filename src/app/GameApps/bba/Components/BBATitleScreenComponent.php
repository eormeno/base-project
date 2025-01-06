<?php

namespace App\GameApps\bba\Components;

use App\Contracts\IPersistent;
use App\Models\Components\StateViewComponent;

class BBATitleScreenComponent extends StateViewComponent implements IPersistent
{
    protected $table = 'bba_title_screen_components';

    public static function config(): array
    {
        return [];
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getFillable(): array
    {
        $config = [];
        // if current class has a config method, then call it
        if (method_exists($this, 'config')) {
            $config = $this->config();
        }
        return array_merge(array_keys($config), ['id']);
    }
}
