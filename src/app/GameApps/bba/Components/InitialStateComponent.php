<?php

namespace App\GameApps\bba\Components;

use App\Traits\HasNamespacePrefix;
use App\Models\Components\PersistentComponent;

class InitialStateComponent extends PersistentComponent
{
	use HasNamespacePrefix;

	public function onEnter(): void
	{
		$this->log('InitialStateComponent::onEnter()');
		$initialView = $this->gameObject->findChild('initial_view');
		if ($initialView) {
			$this->log('InitialStateComponent::onEnter() found initial_view');
			$initialView->updateActive(true);
		}
	}

	public function onExit(): void
	{
		$this->log('InitialStateComponent::onExit()');
		$initialView = $this->gameObject->findChild('initial_view');
		if ($initialView) {
			$this->log('InitialStateComponent::onExit() found initial_view');
			$initialView->updateActive(false);
		}
	}

	public function onStartEvent(): string|null
	{
		return 'playing';
	}

}
