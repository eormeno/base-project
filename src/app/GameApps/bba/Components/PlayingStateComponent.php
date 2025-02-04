<?php

namespace App\GameApps\bba\Components;

use App\Traits\HasNamespacePrefix;
use App\Models\Components\Component;
use App\Models\GameObject\GameObject;
use App\Models\Components\PersistentComponent;
use App\GameApps\Common\Components\SpriteComponent;

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
		$this->move($ball, 1000 / $delta, 800, 450);
	}

	public function move(GameObject $ball, float $delta, $screenWidth, $screenHeight)
	{
		$sprite = $ball->getComponent('sprite');
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

		$sprite->rotation += 5;
		if ($sprite->rotation >= 360) {
			$sprite->rotation = 0;
		}

		$ball->version++; // increment the version to trigger a re-render
		$ball->save();
		$this->save();
		$sprite->save();
	}

	public function onRestartEvent()
	{
		$ball = $this->findGameObject('ball');
		$sprite = $ball->getComponent('sprite');
		$sprite->x = 400;
		$sprite->y = 225;
		$sprite->rotation = 0;
		$sprite->update();
		$this->vx = 5;
		$this->vy = 5;
		$this->save();
	}
}
