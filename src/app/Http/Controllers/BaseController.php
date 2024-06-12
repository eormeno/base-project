<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ReflectionUtils;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequestFilter;

abstract class BaseController extends Controller
{
    public final function index(Request $request)
    {
        $strThisControllerKebabName = $this->name();
        return view("$strThisControllerKebabName.index", [
            'routeName' => $strThisControllerKebabName,
        ]);
    }

    public abstract function event(EventRequestFilter $request);

    public abstract function reset(): void;

    public function _reset()
    {
        $this->reset();
        $str_this_controller_kebab_name = $this->name();
        return redirect()->route($str_this_controller_kebab_name);
    }

    protected function name(): string
    {
        return ReflectionUtils::getKebabClassName($this, 'Controller');
    }
}
