<?php

namespace App\Models\Components;

class WebStateRendererComponent extends WebRendererComponent implements IState
{
    protected $view_name = 'web-renderer.default';
    protected $state = 'default';

    public static function create(array $attributes = [])
    {
        $attributes['active'] = false;
        return new static($attributes);
    }

    public function state(): string
    {
        return $this->state;
    }

    public function onEnter(): void
    {
    }

    public function onExit(): void
    {
    }
}
