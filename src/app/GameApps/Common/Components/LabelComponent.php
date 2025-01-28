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

	public function onAwake(array $initParams): void
	{
		$this->update($initParams);
	}

	public function view()
	{
		return [
			'type' => 'label',
			'text' => $this->text,
			'style' => $this->style,
		];
	}
}
