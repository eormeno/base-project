<?php

namespace App\Models\Components\GTN;

use App\Services\MessageService;
use App\Models\Components\StateViewComponent;

class InitialStateViewComponent extends StateViewComponent
{
    protected $table = 'gtn_initial_state_view_components';
    protected $view_name = 'guess-the-number.initial';

    public static function state(): string|null
    {
        return 'initial';
    }

    public function onStart(): void
    {
        $gtnService = $this->getService('gtn-service');
        //$messages = app(MessageService::class)->getMessages(self::class, $gtnService);
        $messages = [
            'description' => [
                'user_name' => auth()->user()->name,
                'remaining_attemts' => $gtnService->max_attempts,
                'min_number' => $gtnService->min_number,
                'max_number' => $gtnService->max_number,
            ],
            'yes_button' => [],
            'ranking_title' => []
        ];
        $this->updateView($messages);
    }

    public function onWantToPlayEvent()
    {
        return PreparingStateComponent::state();
    }

    // public function messages(): array
    // {
    //     $gtnService = $this->getService('gtn-service')->toArray();
    //     $messages = app(MessageService::class)->getMessages(self::class, $gtnService);
    //     return $messages;
    // }
}
