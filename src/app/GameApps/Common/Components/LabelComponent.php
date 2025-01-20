<?php

namespace App\GameApps\Common\Components;

use App\Models\Components\PersistentComponent;

class LabelComponent extends PersistentComponent
{
    public static function config(): array
    {
        return [
            'text' => ['string', ''],
            'style' => ['string', ''],
        ];
    }

	public function view()
	{
		echo "LabelComponent: view\n";
		return [
			'type' => 'label',
			'text' => $this->text,
			'style' => $this->style,
		];
	}
}
