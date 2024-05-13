<?php

namespace App;

interface FSMContext
{
    public function setState(FSMState $state);

    public function request();

}
