<?php

namespace App\GameApps\bba\Components;

use App\Traits\HasNamespacePrefix;
use App\Models\Components\PersistentComponent;

class InitialStateComponent extends PersistentComponent
{
	use HasNamespacePrefix;

	public function onStartEvent(): string|null
	{
		$this->log('onStartEvent');
		return 'initial';
	}

}
