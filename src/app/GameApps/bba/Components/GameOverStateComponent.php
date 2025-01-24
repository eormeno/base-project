<?php

namespace App\GameApps\bba\Components;

use App\Models\Components\PersistentComponent;

class GameOverStateComponent extends PersistentComponent
{
	public function view()
	{
		return [
			'type' => 'game-over-state',
		];
	}
}
