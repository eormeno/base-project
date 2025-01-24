<?php

namespace App\GameApps\gtn\Components;

use App\Models\Components\StateViewComponent;
use App\Models\Components\PersistentComponent;

class SuccessStateComponent extends PersistentComponent
{
    //protected $table = 'gtn_success_state_components';
    protected $view_name = 'guess-the-number.success';

	protected function getPrefix(): string
	{
		return 'xxxx';
	}

    public function onStart(): void
    {
		parent::onStart();
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
		return 'preparing';
    }

    public function onExitEvent()
    {
		return 'initial';
    }

	public function view()
	{
		$data = $this->messages();
		if (!isset($this->view_name)) {
			$this->view_name = 'default';
		}
		return base64_encode(view($this->view_name, $data));
	}
}
