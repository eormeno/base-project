<?php

namespace App\Services;

use App\Models\Game;
use App\Models\GameObject;

class StateContextService
{
    public function request(Game $game, array $event): array
    {
        $ret = [
            'root' => 'info',
            'info' => base64_encode(json_encode($event)),
            'actives' => []
        ];
        if ($event['event'] === 'reload') {
            $gameObject = $game->gameObject;
            $base64View = base64_encode($gameObject->view());
            $ret = [
                'root' => $gameObject->id,
                $gameObject->id => $base64View,
                'actives' => [$gameObject->id]
            ];
        }
        return $ret;
    }
}
