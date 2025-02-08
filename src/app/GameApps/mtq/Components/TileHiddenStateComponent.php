<?php

namespace App\GameApps\mtq\Components;

use App\Models\Components\Component;

class TileHiddenStateComponent extends Component
{
	public function view()
	{
		return [
			'texture' => 'tile_hidden.png',
		];
	}
}
