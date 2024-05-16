<?php

namespace App\Livewire;

use Livewire\Component;

class DebugBar extends Component
{
    public array $info = [];
    public string $reset_route = '';

    public function mount($info, $include = [], $route = null)
    {
        $this->info = $info;
        if ($route)
            $this->reset_route = $route;
        // Include only the keys that are in the include array
        $this->info = array_intersect_key($this->info, array_flip($include));
        // For each element, if its type is a string, add quotes.
        foreach ($this->info as $key => $value) {
            if (is_string($value)) {
                $this->info[$key] = "'$value'";
            }
        }
    }

    public function render()
    {
        return view('livewire.debug-bar');
    }
}
