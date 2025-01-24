<?php

namespace App\GameApps\bba\Components;

use App\Traits\HasNamespacePrefix;
use App\Models\Components\PersistentComponent;

class PlayingStateComponent extends PersistentComponent
{
	use HasNamespacePrefix;

	public function view()
	{
		return [
			'type' => 'playing-state',
		];
	}
}
