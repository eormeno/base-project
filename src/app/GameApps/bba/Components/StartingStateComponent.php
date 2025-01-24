<?php

namespace App\GameApps\bba\Components;

use App\Models\Components\PersistentComponent;

class StartingStateComponent extends PersistentComponent
{
	public function view()
	{
		return [
			'type' => 'starting-state',
		];
	}
}
