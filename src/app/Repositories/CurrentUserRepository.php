<?php

namespace App\Repositories;

class CurrentUserRepository
{
    public function name()
    {
        return auth()->user()->name;
    }
}
