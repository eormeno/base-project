<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toast extends Component
{
    public bool $show;
    public int $duration;

    public function __construct(bool $show = false, int $duration = 3000)
    {
        $this->show = $show;
        $this->duration = $duration;
    }

    public function render(): View|Closure|string
    {
        return view('components.toast');
    }
}
