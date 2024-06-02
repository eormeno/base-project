<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;

class Success extends StateAbstractImpl
{
    public string $notification = "";
    public string $subtitle = "";
    public string $current_score = "";
    public string $total_score = "";

    public function onEnter(): void
    {
        $this->notification = $this->context->messageService->successMessage();
        $this->subtitle = $this->context->messageService->successSubtitleMessage();
        $this->current_score = $this->context->gameService->calculateScore();
        $this->total_score = $this->context->gameService->totalScore();
        $this->context->gameService->endGame();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'play_again') {
            $this->context->setState(Preparing::class);
        }
    }
}
