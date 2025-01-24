<?php

namespace App\GameApps\bba\Components;

use App\Models\Components\PersistentComponent;

class PlayingStateComponent extends PersistentComponent
{
	public function view()
	{
		return [
			'type' => 'playing-state',
		];
	}
}
