<?php

namespace App\GameApps\mtq\Components;

use App\Models\Components\Component;

class TileRevealedStateComponent extends Component
{
	public function view()
	{
		return [
			'texture' => 'tile_revealed.png',
		];
	}
}
