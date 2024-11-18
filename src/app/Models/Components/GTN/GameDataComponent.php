<?php

namespace App\Models\Components\GTN;

use App\Models\Component;

class GameDataComponent extends Component
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->component_type = 'gtn.game-data';
        $this->properties = [
            'score' => 0,
            'max_attempts' => 10,
            'min_number' => 1,
            'max_number' => 1024,
        ];
    }
}
