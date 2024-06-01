<?php

namespace App\Repositories\Globals;

class UserRepository
{
    public function name()
    {
        return auth()->user()->name;
    }
}
