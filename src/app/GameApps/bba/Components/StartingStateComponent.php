<?php

namespace App\GameApps\bba\Components;

use App\Models\Components\PersistentComponent;

class StartingStateComponent extends PersistentComponent
{
	public static function config(): array
	{
		return [];
	}

	public function view()
	{
		return [
			'type' => 'starting-state',
		];
	}
}
