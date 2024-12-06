<?php

namespace App\Models\Components\GTN;

use App\Models\Components\StateViewComponent;

class SuccessStateComponent extends StateViewComponent
{
    protected $table = 'gtn_success_state_components';
    protected $view_name = 'guess-the-number.success';

    public static function state(): string | null
    {
        return 'success';
    }

    public function messages(): array
    {
        return [];
    }
}
