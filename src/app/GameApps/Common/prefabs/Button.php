<?php
namespace App\GameApps\Common\prefabs;

use App\Models\Prefab;

class Button extends Prefab
{
    public static function structure(): array
    {
        return [
            'components' => [
                'button' => []
            ]
        ];
    }
}
