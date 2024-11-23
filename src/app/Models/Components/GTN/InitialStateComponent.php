<?php

namespace App\Models\Components\GTN;

use App\Models\Components\WebStateRendererComponent;

class InitialStateComponent extends WebStateRendererComponent
{
    protected $table = 'gtn_initial_state_components';
    protected $state = 'initial';
    protected $view_name = null;
}
