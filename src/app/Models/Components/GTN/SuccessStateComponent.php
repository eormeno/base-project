<?php

namespace App\Models\Components\GTN;

use App\Models\Components\StateViewComponent;

class SuccessStateComponent extends StateViewComponent
{
    protected $table = 'gtn_success_state_components';
    protected $view_name = 'guess-the-number.success';

    public static function state(): string|null
    {
        return 'success';
    }

    public function onStart(): void
    {
        $gtn_data = $this->getService('gtn-service');
        $param = [
            'user_name' => $gtn_data->user->name,
            'attempts' => $gtn_data->remaining_free_attempts,
            'score' => $gtn_data->calculateScore(),
            'hscore' => $gtn_data->totalScore(),
        ];
        $messages = [
            'i18n' => [
                "notification" => $param,
                'subtitle' => $param,
                'current_score' => $param,
                'historic_score' => $param,
                'play_again' => null,
                'exit' => null,
            ],
        ];
        $this->updateView($messages);
    }

    public function onPlayAgainEvent()
    {
        return PreparingStateComponent::state();
    }

    public function onExitEvent()
    {
        return InitialStateViewComponent::state();
    }
}
