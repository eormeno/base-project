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
			'playing_view:container' => self::playingView(),
		];
	}

	private static function initialView(): array
	{
		return [
			'active' => false,
			'attributes' => [
				'layout' => 'vertical',
				'image' => 'initial_background.png',
				'width' => '100%',
				'height' => '100%'
			],
			'title:label' => ['attributes' => ['text' => 'Mythic Treasure Quest', 'style' => 'title']],
			'description:label' => ['attributes' => ['text' => 'Un juego donde exploras templos antiguos y encuentras tesoros y posiones usando las mecánicas de buscaminas. Pero ten cuidado! También hay trampas, monstruos y maldiciones.', 'style' => 'paragraph']],
			'accept_text:label' => ['attributes' => ['text' => 'Si aceptas el desafío, presiona el botón para comenzar', 'style' => 'paragraph']],
			'start_button:button' => ['attributes' => ['text' => 'Start', 'event' => 'start', 'style' => 'primary']],
			'title_sound:sound' => ['attributes' => ['sound' => 'title-music.wav', 'loop' => true, 'volume' => 0.25]],
		];
	}

	private static function playingView():array
	{
		return [
			'active' => false,
			'attributes' => [
				'layout' => 'vertical',
				'image' => 'playing_background.png',
				'width' => '100%',
				'height' => '100%'
			],
			'start_button:button' => ['attributes' => ['text' => 'Back', 'event' => 'start', 'style' => 'primary']],
			'tile0:mtq.tile' => ['attributes' => ['x' => 0, 'y' => 0]],
			'tile_1:sprite' => [
				'attributes' => [
					'texture' => 'tile_hidden.png',
					'width' => 32,
					'height' => 32,
					'x' => 0,
					'y' => 0
				]
			],
		];
	}

	private static function states(): array
	{
		return [
			'initial' => ['mtq.initial-state' => []],
			'playing' => ['mtq.playing-state' => []],
		];
	}
}
