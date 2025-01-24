<?php

namespace App\GameApps\bba\Components;

use App\Models\Components\PersistentComponent;

class InitialStateComponent extends PersistentComponent
{
	public function view()
	{
		return [
			'type' => 'initial-state',
		];
	}
}
