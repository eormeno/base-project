<?php

namespace App\GameApps\gtn\Components;

trait HasGTNPrefix
{
	protected function getPrefix(): string
	{
		return 'gtn';
	}
}
