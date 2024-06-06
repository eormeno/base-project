<?php

namespace App\States\GuessTheNumber;

use App\FSM\StateAbstractImpl;
use App\Exceptions\GuessTheNumber\FailException;
use App\Exceptions\GuessTheNumber\InfoException;
use App\Exceptions\GuessTheNumber\SuccessException;
use App\Exceptions\GuessTheNumber\GameOverException;
use App\Exceptions\GuessTheNumber\NotInRangeException;

class Playing extends StateAbstractImpl
{
    public string $notification = "";

    public function onEnter(): void
    {
        $this->notification = $this->context->messageService->remainingAttemptsMessage();
    }

    public function onGuessEvent(int $number = -1)
    {
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
