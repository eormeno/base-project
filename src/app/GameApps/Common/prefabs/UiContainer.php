<?php
namespace App\GameApps\Common\prefabs;

use App\Models\Prefab;
use App\Models\GameObject\GameObject;

class UiContainer extends Prefab
{
    public function afterInstantiate(GameObject $gameObject, array $attributes = []): void
    {
        echo "UiContainer instantiated\n";
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
