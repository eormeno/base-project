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
			'state' => ['string', 'hidden'],
        ];
    }

	public function onAwake(array $initParams): void
	{
		$this->x = $initParams['x'] ?? 0;
		$this->y = $initParams['y'] ?? 0;
		$this->state = $initParams['state'] ?? 'hidden';
		$this->save();
	}

	public function view()
	{
		return [
			'parent' => $this->parentGameObject()->id ?? null,
			'type' => 'sprite',
			'texture' => "tile_{$this->state}.png",
			'x' => $this->x,
			'y' => $this->y,
			'layer' => 1,
			'pivot_x' => 0.5,
			'pivot_y' => 0.5,
			'width' => 32,
			'height' => 32,
			'rotation' => 0,
		];
	}
}
