<?php

namespace App\GameApps\Components;

use App\Models\Components\Component;

class SpriteRendererComponent extends Component
{
    protected $fillable = ['id', 'texture', 'layer'];
}
