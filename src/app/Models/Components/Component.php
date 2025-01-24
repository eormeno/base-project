<?php

namespace App\Models\Components;

class Component extends ComponentStateManager
{
	public function onAwake(): void
	{
	}

	public function onEnter(): void
	{
		//$this->log(class_basename($this) . '::onEnter()');
	}

	public function onExit(): void
	{
		//$this->log(class_basename($this) . '::onExit()');
	}

	public function passTo(): string|null
	{
		return null;
	}

	public function onStart(): void
	{
		// $this->log(class_basename($this) . '::onStart()');
	}

	public function onUpdate(float $delta): void
	{
	}
}
