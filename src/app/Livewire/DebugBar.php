<?php

namespace App\Livewire;

use Livewire\Component;

class DebugBar extends Component
{
    public $info_source = 'info';
    public array $info = [];
    public array $include = [];
    public string $reset_route = '';

    public function mount($info_source = 'info', $include = [], $route = null)
    {
        $this->include = $include;
        $this->info_source = $info_source;
        $this->info = session($this->info_source, []);
        if ($route)
            $this->reset_route = $route;
        $this->applyFilter();
    }

    public function updateInfo()
    {
        $this->info = session($this->info_source, []);
        $this->applyFilter();
    }

    public function render()
    {
        return view('livewire.debug-bar');
    }

    private function applyFilter()
    {
        // Include only the keys that are in the include array
        $this->info = array_intersect_key($this->info, array_flip($this->include));
        // For each element, if its type is a string, add quotes.
        foreach ($this->info as $key => $value) {
            if (is_string($value)) {
                $this->info[$key] = "'$value'";
            }
        }
    }
}
