<?php

namespace App\Models\Components;

use App\Contracts\IPersistent;

abstract class PersistentComponent extends StateViewComponent implements IPersistent
{
    public function getTable(): string
    {
        return parent::getTable();
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
