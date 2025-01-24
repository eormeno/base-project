<?php

namespace App\GameApps\bba\Components;

use App\Traits\HasNamespacePrefix;
use App\Models\Components\PersistentComponent;

class GameOverStateComponent extends PersistentComponent
{
	use HasNamespacePrefix;

	public function view()
	{
		return [
			'type' => 'game-over-state',
		];
	}
}
