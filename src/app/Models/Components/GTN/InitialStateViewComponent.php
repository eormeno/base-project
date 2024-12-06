<?php

namespace App\Models\Components\GTN;

use App\Services\MessageService;
use App\Models\Components\StateViewComponent;

class InitialStateViewComponent extends StateViewComponent
{
    protected $table = 'gtn_initial_state_view_components';
    protected $view_name = 'guess-the-number.initial';

    public static function state(): string | null
    {
        return 'initial';
    }

    public function onWantToPlayEvent()
    {
        return PreparingStateComponent::state();
    }

    public function messages(): array
    {
        $gtnService = $this->getService('gtn-service')->toArray();
        $messages = app(MessageService::class)->getMessages(self::class, $gtnService);
        return $messages;
    }
}
