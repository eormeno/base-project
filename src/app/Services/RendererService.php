<?php

namespace App\Services;

use App\Models\Game;
use App\Events\FrontEvent;
use App\Contracts\IRenderer;

class RendererService implements IRenderer
{
    public function render(Game $game, array $eventInfo): array
    {
        event(new FrontEvent($game, $eventInfo));
        return $this->request($game, $eventInfo);
    }

    private function request(Game $game, array $event): array
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
