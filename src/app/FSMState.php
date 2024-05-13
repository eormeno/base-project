<?php

namespace App;

interface FSMState
{
    public function handleRequest(FSMContext $context);
}
