<?php

namespace App\Services\GuessTheNumber\MessageComponents;

use App\Services\GuessTheNumber\AbstractComponent;

class ShowingClueMessages extends AbstractComponent
{
    public function title(): string
    {
        return __('guess-the-number.showing-clue');
    }

    public function goodLuck(): string
    {
        return __('guess-the-number.good-luck');
    }

    public function clueMessageFor(array $clue): string
    {
        $clueKey = 'guess-the-number.clue-' . $clue['clue'];
        $clueData = '';
        if (is_array($clue['data'])) {
            $clueData = implode(', ', $clue['data']);
        } else {
            $clueData = $clue['data'];
        }
        return __($clueKey, ['data' => $clueData]);
    }

    public function clues(): array
    {
        $clues = $this->clueService->getClues();
        $ret = [];
        foreach ($clues as $clue) {
            $ret[] = $this->clueMessageFor($clue);
        }
        return $ret;
    }

}
