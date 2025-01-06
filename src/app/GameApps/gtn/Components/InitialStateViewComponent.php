<?php

namespace App\GameApps\gtn\Components;

use App\Models\Components\StateViewComponent;

class InitialStateViewComponent extends StateViewComponent
{
    protected $table = 'gtn_initial_state_view_components';
    protected $view_name = 'guess-the-number.initial';

    public function onStart(): void
    {
        $gtn_service = $this->getService('gtn-service');
        $description_params = [
            'user_name' => $gtn_service->user->name,
            'min_number' => $gtn_service->min_number,
            'max_number' => $gtn_service->max_number,
            'max_attempts' => $gtn_service->max_attempts,
        ];
        $ranking = [
            ['name' => 'Jugador 1', 'score' => 100],
            ['name' => 'Jugador 2', 'score' => 90],
            ['name' => 'Jugador 3', 'score' => 80],
            ['name' => 'Jugador 4', 'score' => 70],
            ['name' => 'Jugador 5', 'score' => 60],
        ];

        $result = $this->updateView([
            'i18n' => [
                'description' => $description_params,
                'yes_button' => null,
                'ranking_title' => null,
            ],
            'ranking' => $ranking
        ]);
        if (!$result) {
            $this->view_name = 'debug';
        }
    }

    public function onWantToPlayEvent()
    {
		return 'preparing';
    }
}
