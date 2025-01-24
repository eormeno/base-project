<?php

namespace App\GameApps\gtn\Components;

use App\Models\Components\PersistentComponent;

class InitialStateViewComponent extends PersistentComponent
{
	//protected $table = 'gtn_initial_state_view_components';
	protected $view_name = 'guess-the-number.initial';

	protected function getPrefix(): string
	{
		return 'xxxx';
	}

	public function onStart(): void
	{
		parent::onStart();
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

	public function view()
	{
		$data = $this->messages();
		if (!isset($this->view_name)) {
			$this->view_name = 'default';
		}
		return base64_encode(view($this->view_name, $data));
	}
}
