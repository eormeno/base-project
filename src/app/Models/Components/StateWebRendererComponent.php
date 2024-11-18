<?php

namespace App\Models\Components;

use App\Models\Component;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StateWebRendererComponent extends Component
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->component_type = 'state-web-renderer';
        $this->properties = [
            'rendered_state' => 'initial',
            'slot' => 'main',
            'provided_slots' => [],
        ];
    }
}
