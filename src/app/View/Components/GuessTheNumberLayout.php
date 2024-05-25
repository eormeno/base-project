<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GuessTheNumberLayout extends Component
{
    public $info;

    public function __construct($info = null)
    {
        $this->info = session('info');
        $this->description = $this->info['description'];
        $this->notification = $this->info['notification'];
    }

    public function render(): View|Closure|string
    {
        return view('guess-the-number.layout');
    }
}
