<?php

namespace App\GameApps\bba\Components;

use App\Traits\HasNamespacePrefix;
use Illuminate\Support\Facades\DB;
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
		$x = $sprite->x;
		$y = $sprite->y;

		// Actualizar posición
		$x += $this->vx * $delta;
		$y += $this->vy * $delta;

		// Detección de colisiones
		if ($x <= $sprite_width || $x + $sprite_width >= $screenWidth) {
			$this->vx = -$this->vx;
			$x = max($sprite_width, min($x, $screenWidth - $sprite_width));
		}
		if ($y <= $sprite_height || $y + $sprite_height >= $screenHeight) {
			$this->vy = -$this->vy;
			$y = max($sprite_height, min($y, $screenHeight - $sprite_height));
		}

		// Rotación
		$newRotation = ($sprite->rotation + 5) % 360;

		// Determinar si es necesario guardar
		$needsSave = false;
		if ($x !== $sprite->x || $y !== $sprite->y || $newRotation !== $sprite->rotation) {
			$sprite->x = $x;
			$sprite->y = $y;
			$sprite->rotation = $newRotation;
			$needsSave = true;
		}

		// Guardar cambios solo si es necesario
		if ($needsSave || $ball->isDirty() || $sprite->isDirty()) {
			DB::transaction(function () use ($ball, $sprite) {
				$ball->version++;
				$ball->save();
				$sprite->save();
				$this->save();
			});
		}
	}

	public function move3(GameObject $ball, float $delta, $screenWidth, $screenHeight)
	{
		$sprite = $ball->getComponent('sprite');
		$sprite_width = $sprite->width * $sprite->scale;
		$sprite_height = $sprite->height * $sprite->scale;

		// Update the ball's position based on its velocity
		$sprite->x += $this->vx * $delta;
		$sprite->y += $this->vy * $delta;

		// Check for collisions with screen boundaries and adjust velocity
		if ($sprite->x <= $sprite_width || $sprite->x + $sprite_width >= $screenWidth) {
			$this->vx = -$this->vx;
			$sprite->x = max($sprite_width, min($sprite->x, $screenWidth - $sprite_width));
		}

		if ($sprite->y <= $sprite_height || $sprite->y + $sprite_height >= $screenHeight) {
			$this->vy = -$this->vy;
			$sprite->y = max($sprite_height, min($sprite->y, $screenHeight - $sprite_height));
		}

		// Update rotation and reset if necessary
		$sprite->rotation = ($sprite->rotation + 5) % 360;

		// Save changes only if necessary
		if ($ball->isDirty() || $sprite->isDirty()) {
			$ball->version++; // increment the version to trigger a re-render
			$ball->save();
			$sprite->save();
		}
		$this->save();
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
