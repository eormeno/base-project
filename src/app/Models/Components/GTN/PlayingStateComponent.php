<?php

namespace App\Models\Components\GTN;

use App\Services\MessageService;
use App\Models\Components\StateViewComponent;

class PlayingStateComponent extends StateViewComponent
{
    protected $table = 'gtn_playing_state_components';
    protected $view_name = 'guess-the-number.playing';

    public static function state(): string|null
    {
        return 'playing';
    }

    public function onStart(): void
    {
        $gtn_data = $this->getService('gtn-service')->toArray();
        //        $messages = app(MessageService::class)->getMessages(self::class, $gtn_data);
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
        $this->updateView(['i18n' => ['results' => $result]]);
        //        $this->log(json_encode($result));
    }

    // public function messages(): array
    // {
    //     $gtn_data = $this->getService('gtn-service')->toArray();
    //     $messages = app(MessageService::class)->getMessages(self::class, $gtn_data);
    //     return $messages;
    // }
}
