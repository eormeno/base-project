<?php
namespace App\GameApps\Common\prefabs;

use App\Models\Prefab;

class UiContainer extends Prefab
{
	public static function components(): array
	{
		return [
			'ui-container' => []
		];
	}

    public static function structure(): array
    {
        return [
            'components' => [
                'ui-container' => []
            ]
        ];
    }
}
