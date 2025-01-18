<?php

namespace App\Services;

use App\Models\Game;
use App\Events\FrontEvent;
use App\Contracts\IRenderer;

class RendererService implements IRenderer
{
    public function render(Game $game, array $eventInfo): array
    {
        $currentTimestamp = microtime(true);
        event(new FrontEvent($game, $eventInfo));
        $result = $this->request($game, $eventInfo);
        $elapsed = ceil((microtime(true) - $currentTimestamp) * 1000);
		$result['elapsed'] = $elapsed;
        return $result;
    }

    private function request(Game $game, array $event): array
    {
		$client = $game->gameApp->client;
        $gameObject = $game->gameObject;
		$view = $client == 'webgl' ? $gameObject->view() : base64_encode($gameObject->view());
        $ret = [
			'elapsed' => 0,
            'root' => $gameObject->id,
            $gameObject->id => $view,
            'actives' => [$gameObject->id]
        ];
        return $ret;
    }
}
