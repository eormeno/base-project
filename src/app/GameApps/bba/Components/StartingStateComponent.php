<?php

namespace App\GameApps\bba\Components;

use App\Traits\HasNamespacePrefix;
use App\Models\Components\PersistentComponent;

class StartingStateComponent extends PersistentComponent
{
	use HasNamespacePrefix;

	public function view()
	{
		return [
			'type' => 'starting-state',
		];
	}
}
