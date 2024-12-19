<?php

namespace App\Services;

use App\Models\Game;
use App\Events\FrontEvent;
use App\Traits\DebugHelper;
use App\Contracts\IRenderer;

class RendererService implements IRenderer
{
    use DebugHelper;

    public function render(Game $game, array $eventInfo): array
    {
        $currentTimestamp = microtime(true);
        event(new FrontEvent($game, $eventInfo));
        $result = $this->request($game, $eventInfo);
        $elapsed = ceil((microtime(true) - $currentTimestamp) * 1000);
        $this->log("Back rendered in $elapsed ms");
        return $result;
    }

    private function request(Game $game, array $event): array
    {
        // TODO Eliminar la inicialización de este array
        $ret = [
            'root' => 'info',
            'info' => base64_encode(json_encode($event)),
            'actives' => []
        ];
        $gameObject = $game->gameObject;
        $base64View = base64_encode($gameObject->view());
        $ret = [
            'root' => $gameObject->id,
            $gameObject->id => $base64View,
            'actives' => [$gameObject->id]
        ];
        return $ret;
    }
}
