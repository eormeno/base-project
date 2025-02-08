<?php

namespace App\GameApps\mtq\Components;

use App\Traits\HasNamespacePrefix;
use App\Models\Components\PersistentComponent;

class InitialStateComponent extends PersistentComponent
{
	use HasNamespacePrefix;

	public function onEnter(): void
	{
		$initialView = $this->gameObject->findChild('initial_view');
		if ($initialView) {
			$initialView->updateActive(true);
		}
	}

	public function onExit(): void
	{
		$initialView = $this->gameObject->findChild('initial_view');
		if ($initialView) {
			$initialView->updateActive(false);
		}
	}

	public function onStartEvent(): string|null
	{
		return 'playing';
	}

}
