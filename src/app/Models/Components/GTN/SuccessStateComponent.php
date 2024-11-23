<?php

namespace App\Models\Components\GTN;

use App\Models\Components\WebStateRendererComponent;

class SuccessStateComponent extends WebStateRendererComponent
{
    protected $table = 'gtn_success_state_components';
    protected $view_name = 'guess-the-number.success';
    protected $state = 'success';
}
