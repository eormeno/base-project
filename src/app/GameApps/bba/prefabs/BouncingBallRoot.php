<?php

namespace App\GameApps\bba\prefabs;

use App\Models\Prefab\Prefab;

class BouncingBallRoot extends Prefab
{

	public static function structure(): array
	{
		return [
			'states' => self::states(),
			'initial_view:container' => self::initialView(),
			'playing_view:container' => self::playingView(),
			'game_over_view:container' => self::gameOverView(),
		];
	}

	private static function initialView(): array
	{
		return [
			'active' => false,
			'attributes' => ['layout' => 'vertical', 'image' => 'background.jpeg'],
			'title:label' => ['attributes' => ['text' => 'Bouncing Ball', 'style' => 'title']],
			'start_button:button' => ['attributes' => ['text' => 'Start', 'event' => 'start', 'style' => 'primary']],
		];
	}

	private static function playingView(): array
	{
		return [
			'active' => false,
			'attributes' => ['layout' => 'vertical'],
			'title:label' => ['attributes' => ['text' => 'Playing', 'style' => 'title']],
			'start_button:button' => ['attributes' => ['text' => 'Restart', 'event' => 'restart']],
		];
	}

	private static function gameOverView(): array
	{
		return [
			'active' => false,
			'attributes' => ['layout' => 'vertical'],
			'title:label' => ['attributes' => ['text' => 'Game Over', 'style' => 'title']],
			'start_button:button' => ['attributes' => ['text' => 'Restart', 'event' => 'restart']],
		];
	}

	private static function states(): array
	{
		return [
			'initial' => ['bba.initial-state' => []],
			'start' => ['bba.starting-state' => []],
			'playing' => ['bba.playing-state' => []],
			'game_over' => ['bba.game-over-state' => []],
		];
	}
}
