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
			'menu_view:container' => self::menuView(),
			'playing_view:container' => self::playingView(),
		];
	}

	private static function initialView()
	{
		return [
			'active' => true,
			'attributes' => [
				'layout' => 'vertical',
				'image' => 'initial_background.png',
				'width' => '100%',
				'height' => '100%'
			],
			'title:label' => ['attributes' => ['text' => 'Mythic Treasure Quest', 'style' => 'title']],
			'start_button:button' => ['attributes' => ['text' => 'Start', 'event' => 'start', 'style' => 'primary']],
		];
	}

	private static function menuView(): array
	{
		return [
			'active' => false,
			'attributes' => [
				'layout' => 'vertical',
				'image' => 'menu_background.png',
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

	private static function playingView(): array
	{
		return [
			'active' => false,
			'attributes' => [
				'layout' => 'vertical',
				'image' => 'playing_background.png',
				'width' => '100%',
				'height' => '100%'
			],
			// 'back_button:button' => ['attributes' => ['text' => 'Back', 'event' => 'start', 'style' => 'primary']],
			'tileset:container' => self::tileMatrix(8, 8),
		];
	}

	private static function tileMatrix(int $rows, int $cols): array
	{
		$size = 64;
		$hgap = 4;
		$vgap = 4;
		$matrix = [];
		$matrix['active'] = true;
		$matrix['attributes'] = ['x' => 40, 'y' => 40, 'image' => 'tileset_background.png', 'width' => '540px', 'height' => '540px'];
		for ($i = 0; $i < $rows; $i++) {
			for ($j = 0; $j < $cols; $j++) {
				$x = $i * $size + $i * $hgap;
				$y = $j * $size + $j * $vgap;
				$matrix["tile{$i}{$j}:mtq.tile"] = ['attributes' => ['x' => $x, 'y' => $y]];
			}
		}
		return $matrix;
	}

	private static function states(): array
	{
		return [
			'initial' => ['mtq.initial-state' => []],
			'menu' => ['mtq.menu-state' => []],
			'playing' => ['mtq.playing-state' => []],
		];
	}
}
