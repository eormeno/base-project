<?php

namespace App\GameApps\mtq\Components;

use App\Traits\HasNamespacePrefix;
use App\Models\Components\PersistentComponent;

class TileComponent extends PersistentComponent
{
	use HasNamespacePrefix;

    public static function config(): array
    {
        return [
            'x' => ['integer', 0],
            'y' => ['integer', 0],
        ];
    }

	public function onAwake(array $initParams): void
	{
		$this->x = $initParams['x'] ?? 0;
		$this->y = $initParams['y'] ?? 0;
		$this->save();
	}

	public function view()
	{
		return [
			'parent' => $this->parentGameObject()->id ?? null,
			'type' => 'sprite',
			'x' => $this->x,
			'y' => $this->y,
		];
	}
}
