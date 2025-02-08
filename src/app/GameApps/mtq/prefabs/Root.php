<?php

namespace App\GameApps\mtq\prefabs;

use App\Models\Prefab\Prefab;

class Root extends Prefab
{

	public static function structure(): array
	{
		return [
			'states' => self::states(),
			'initial_view:container' => self::initialView(),
		];
	}

	private static function initialView(): array
	{
		return [
			'active' => false,
			'attributes' => [
				'layout' => 'vertical',
				'image' => 'inicial_600x700.png',
				'width' => '100%',	// Expands horizontally to fill the parent container
				'height' => '100%'	// Expands vertically to fill the parent container
			],
			'title:label' => ['attributes' => ['text' => 'Mythic Treasure Quest', 'style' => 'title']],
			'description:label' => ['attributes' => ['text' => 'Un juego donde exploras templos antiguos y encuentras tesoros y posiones usando las mecánicas de buscaminas. Pero ten cuidado! También hay trampas, monstruos y maldiciones.', 'style' => 'paragraph']],
			'accept_text:label' => ['attributes' => ['text' => 'Si aceptas el desafío, presiona el botón para comenzar', 'style' => 'paragraph']],
			'start_button:button' => ['attributes' => ['text' => 'Start', 'event' => 'start', 'style' => 'primary']],
			'title_sound:sound' => ['attributes' => ['sound' => 'title-music.wav', 'loop' => true, 'volume' => 0.25]],
		];
	}

	private static function states(): array
	{
		return [
			'initial' => ['bba.initial-state' => []],
		];
	}
}
