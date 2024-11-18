<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateWebRendererComponent extends Component
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->component_type = 'state-web-renderer';
        $this->properties = [
            'slot' => 'main',
            'rendered_state' => 'initial',
        ];
    }
}
