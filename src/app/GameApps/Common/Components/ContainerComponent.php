<?php

namespace App\GameApps\Common\Components;

use App\Models\Components\PersistentComponent;


class ContainerComponent extends PersistentComponent
{
	public static function config(): array
	{
		return [
			'layout' => ['string', null],
		];
	}

	public function onAwake(array $initParams): void
	{
		$this->layout = $initParams['layout'] ?? 'vertical';
		$this->save();
	}

	public function view()
	{
		return [
			'parent' => $this->parentGameObject()->id ?? null,
			'type' => 'container',
			'layout' => $this->layout,
		];
	}
}
