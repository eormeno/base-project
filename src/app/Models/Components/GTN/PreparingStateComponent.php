<?php

namespace App\Models\Components\GTN;

use App\Models\Components\WebStateRendererComponent;

class PreparingStateComponent extends WebStateRendererComponent
{
    protected $table = 'gtn_preparing_state_components';
    protected $view_name = 'guess-the-number.preparing';
    protected $state = 'preparing';
}
