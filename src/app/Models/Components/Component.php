<?php

namespace App\Models\Components;

class Component extends ComponentMessages
{

	public function onAwake(): void
	{
	}

	public function onStart(): void
	{
		$this->log(class_basename($this) . '::onStart()');
	}

	public function onUpdate(float $delta): void
	{
	}
}
