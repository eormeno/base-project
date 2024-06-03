<?php

namespace App\Repositories\Globals;
use App\Services\AbstractServiceComponent;

class UserRepository extends AbstractServiceComponent
{
    public function name()
    {
        return auth()->user()->name;
    }
}
