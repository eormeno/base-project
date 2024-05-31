<?php

namespace App\Repositories;

class UserRepository
{
    public function name()
    {
        return auth()->user()->name;
    }
}
