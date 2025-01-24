<?php

namespace App\GameApps\gtn\Components;

use App\Models\Components\StateViewComponent;
use App\Models\Components\PersistentComponent;

class PreparingStateComponent extends PersistentComponent
{
	// protected $table = 'gtn_preparing_state_components';
	protected $view_name = 'guess-the-number.preparing';

	protected function getPrefix(): string
	{
		return 'xxxx';
	}

	public function onStart(): void
	{
		parent::onStart();
		$this->getService('gtn-service')->startGame();
	}

	public function passTo(): string|null
	{
		return 'showing_clue';
	}

	public function view()
	{
		$data = $this->messages();
		if (!isset($this->view_name)) {
			$this->view_name = 'default';
		}
		return base64_encode(view($this->view_name, $data));
	}
}
