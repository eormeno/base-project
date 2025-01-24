<?php

namespace App\GameApps\gtn\Components;

use App\Models\Components\StateViewComponent;
use App\Models\Components\PersistentComponent;

class PlayingStateComponent extends PersistentComponent
{
	//protected $table = 'gtn_playing_state_components';
	protected $view_name = 'guess-the-number.playing';

	protected function getPrefix(): string
	{
		return 'xxxx';
	}

	public function onStart(): void
	{
		parent::onStart();
		$gtn_data = $this->getService('gtn-service')->toArray();
		$messages = [
			'i18n' => [
				"remaining_attempts_message" => $gtn_data['remaining_message'],
				'enter_number_message' => null,
				'enter_number_button' => null,
			],
			'finished' => $gtn_data['finished'],
			'last_number' => $gtn_data['last_number'],
		];
		$this->updateView($messages);
	}

	public function onGuessEvent(?int $number = -1)
	{
		$result = $this->getService('guess-service')->guess($number);

		if (array_key_exists('guess_result.success', $result)) {
			return 'success';
		} else if (array_key_exists('guess_result.game_over', $result)) {
			return 'game_over';
		}

		$this->onStart();
		$this->updateView(['i18n' => ['results' => $result]]);
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
