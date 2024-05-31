<?php

namespace App\Http\Controllers\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\Http\Controllers\GuessTheNumber\Services\FailException;
use App\Http\Controllers\GuessTheNumber\Services\InfoException;
use App\Http\Controllers\GuessTheNumber\Services\SuccessException;
use App\Http\Controllers\GuessTheNumber\Services\GameOverException;
use App\Http\Controllers\GuessTheNumber\Services\NotInRangeException;

class Playing extends StateAbstractImpl
{
    public string $notification = "";

    public function onEnter(): void
    {
        $this->notification = $this->context->messageService->remainingAttemptsMessage();
    }

    public function handleRequest(?string $event = null, $data = null)
    {
        if ($event == 'guess') {
            $number = $data['number'] ?? -1;
            try {
                $this->context->gameService->guess($number);
            } catch (SuccessException $e) {
                $this->context->setState(Success::class);
            } catch (GameOverException $e) {
                $this->context->setState(GameOver::class);
            } catch (InfoException $e) {
                $this->infoToast($e->getMessage());
            } catch (NotInRangeException $e) {
                $this->errorToast($e->getMessage());
            } catch (FailException $e) {
                $this->warningToast($e->getMessage());
            }
            $this->notification = $this->context->messageService->remainingAttemptsMessage();
        }
    }

}
