<?php

namespace App\GameApps\gtn\Components;

use App\Models\Components\PersistentComponent;

class GameDataComponent extends PersistentComponent
{
	//protected $table = 'gtn_game_data_components';

	public function getPrefix(): string
	{
		return 'xxxx';
	}

	public static function config(): array
	{
		return [
			'score' => ['integer', 0],
			'max_attempts' => ['integer', 10],
			'min_number' => ['integer', 1],
			'max_number' => ['integer', 1024],
			'attempts' => ['integer', 0],
			'random_number' => ['integer', 0],
			'otron' => ['integer', 0],
		];
	}


	// protected $fillable = ['id', 'score', 'max_attempts', 'min_number', 'max_number', 'attempts', 'random_number'];

	// public function super(): BelongsTo
	// {
	//     return $this->belongsTo(Component::class, 'id', 'id');
	// }
}
