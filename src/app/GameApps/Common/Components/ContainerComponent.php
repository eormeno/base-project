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

	public function view()
	{
		return [
			'type' => 'container',
			'layout' => $this->layout,
			'children' => $this->children,
		];
	}
}
