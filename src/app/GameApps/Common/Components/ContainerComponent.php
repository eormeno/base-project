<?php

namespace App\GameApps\Common\Components;

use App\Models\Components\PersistentComponent;


class ContainerComponent extends PersistentComponent
{
	public static function config(): array
	{
		return [
			'layout' => ['string', null],
			'width' => ['string', null],
			'height' => ['string', null],
			'image' => ['string', null],
		];
	}

	public function onAwake(array $initParams): void
	{
		$this->layout = $initParams['layout'] ?? 'vertical';
		$this->width = $initParams['width'] ?? null;
		$this->height = $initParams['height'] ?? null;
		$this->image = $initParams['image'] ?? null;
		$this->save();
	}

	public function view()
	{
		return [
			'parent' => $this->parentGameObject()->id ?? null,
			'type' => 'container',
			'layout' => $this->layout,
			'width' => $this->width,
			'height' => $this->height,
			'image' => $this->image,
		];
	}
}
