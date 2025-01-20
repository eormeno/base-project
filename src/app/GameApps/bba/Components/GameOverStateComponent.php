<?php

namespace App\GameApps\bba\Components;

use App\Models\Components\PersistentComponent;

class GameOverStateComponent extends PersistentComponent
{
    public static function config(): array
    {
        return [];
    }

	public function view()
	{
		return [
			'type' => 'game-over-state',
		];
	}
}
