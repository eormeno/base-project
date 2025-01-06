<?php

namespace App\GameApps\gtn\Components;

use App\Models\Components\StateViewComponent;

class PreparingStateComponent extends StateViewComponent
{
	protected $table = 'gtn_preparing_state_components';
	protected $view_name = 'guess-the-number.preparing';

	public function onStart(): void
	{
		$this->getService('gtn-service')->startGame();
	}

	public function passTo(): string|null
	{
		return 'showing_clue';
	}
}
