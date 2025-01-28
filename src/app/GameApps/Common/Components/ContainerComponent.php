<?php

namespace App\GameApps\Common\Components;

use App\Models\Components\PersistentComponent;


class ContainerComponent extends PersistentComponent
{
	public static function config(): array
	{
		return [
			'layout' => ['string', null],
			'children' => ['json', null],
		];
	}

	public function onAwake(array $initParams): void
	{
		$gameObject = $this->gameObject;
		// echo $gameObject . ".ContainerComponent::onAwake(" . json_encode($initParams) . ")\n";
		$this->layout = $initParams['layout'] ?? 'vertical';
		$this->children = $gameObject->children()->pluck('id')->toArray();
		$this->save();
	}

	public function view()
	{
		return [
			'type' => 'container',
			'layout' => $this->layout,
			'children' => $this->children,
		];
	}
}
