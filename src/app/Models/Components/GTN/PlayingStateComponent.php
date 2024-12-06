<?php

namespace App\Models\Components\GTN;

use App\Services\MessageService;
use App\Models\Components\StateViewComponent;

class PlayingStateComponent extends StateViewComponent
{
    protected $table = 'gtn_playing_state_components';
    protected $view_name = 'guess-the-number.playing';

    public static function state(): string | null
    {
        return 'playing';
    }

    public function onGuessEvent(?int $number = -1)
    {
        //$this->getService('gtn-service')->guess($number);
        $this->log('Guess event ' . $number);
    }

    public function messages(): array
    {
        $gtn_data = $this->getService('gtn-service')->toArray();
        $messages = app(MessageService::class)->getMessages(self::class, $gtn_data);
        return $messages;
    }
}
