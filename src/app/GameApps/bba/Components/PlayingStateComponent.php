<?php

namespace App\GameApps\bba\Components;

use App\Traits\HasNamespacePrefix;
use App\Models\Components\PersistentComponent;
use App\GameApps\Common\Components\SpriteComponent;
use App\Models\Components\Component;

class PlayingStateComponent extends PersistentComponent
{
	use HasNamespacePrefix;

	public static function config(): array
	{
		return [
			'vx' => ['float', 5],
			'vy' => ['float', 5],
		];
	}

	public function onEnter(): void
	{
		$playingView = $this->gameObject->findChild('playing_view');
		if ($playingView) {
			$this->log('PlayingStateComponent::onEnter() found playing_view');
			$playingView->updateActive(true);
		}
	}

	public function onExit(): void
	{
		$playingView = $this->gameObject->findChild('playing_view');
		if ($playingView) {
			$this->log('PlayingStateComponent::onExit() found playing_view');
			$playingView->updateActive(false);
		}
	}

	public function onUpdateEvent(float $delta = 0): void
	{
		if ($delta == 0) {
			return;
		}
		$ball = $this->findGameObject('ball');
		$sprite = $ball->getComponent('sprite');
		$this->move($sprite, 1000 / $delta, 800, 450);
	}

	public function move(Component $sprite, float $delta, $screenWidth, $screenHeight)
	{
		$sprite_width = $sprite->width * $sprite->scale;
		$sprite_height = $sprite->height * $sprite->scale;
		// Update the ball's position based on its velocity
		$sprite->x += $this->vx * $delta;
		$sprite->y += $this->vy * $delta;

		if ($sprite->x <= $sprite_width) {
			$sprite->x = $sprite_width;
			$this->vx = -$this->vx;
		}

		if ($sprite->x + $sprite_width >= $screenWidth) {
			$sprite->x = $screenWidth - $sprite_width;
			$this->vx = -$this->vx;
		}

		if ($sprite->y <= $sprite_height) {
			$sprite->y = $sprite_height;
			$this->vy = -$this->vy;
		}

		if ($sprite->y + $sprite_height >= $screenHeight) {
			$sprite->y = $screenHeight - $sprite_height;
			$this->vy = -$this->vy;
		}

		$sprite->rotation += 40;
		if ($sprite->rotation >= 360) {
			$sprite->rotation = 0;
		}

		// // Check for collision with the left or right edges of the screen
		// if ($sprite->x <= 0 || $sprite->x + $sprite->width >= $screenWidth) {
		//     $this->vx = -$this->vx; // Reverse the x velocity
		// }

		// // Check for collision with the top or bottom edges of the screen
		// if ($sprite->y <= 0 || $sprite->y + $sprite->height >= $screenHeight) {
		//     $this->vy = -$this->vy; // Reverse the y velocity
		// }
		$this->update();
		$sprite->update();
	}

	public function onRestartEvent()
	{
		$ball = $this->findGameObject('ball');
		$sprite = $ball->getComponent('sprite');
		$sprite->x = 400;
		$sprite->y = 225;
		$sprite->rotation = 0;
		$sprite->update();
	}
}
