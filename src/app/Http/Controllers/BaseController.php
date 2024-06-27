<?php

namespace App\Http\Controllers;

use App\Services\AbstractServiceManager;
use Illuminate\Http\Request;
use App\Utils\ReflectionUtils;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequestFilter;

abstract class BaseController extends Controller
{
    protected AbstractServiceManager $serviceManager;

    public function __construct(AbstractServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public final function index(Request $request)
    {
        $strThisControllerKebabName = $this->serviceManager->baseKebabName();
        return view("$strThisControllerKebabName.index", [
            'routeName' => $strThisControllerKebabName,
        ]);
    }

    public abstract function event(EventRequestFilter $request);

    public abstract function reset(): void;

    public function _reset()
    {
        $this->reset();
        $str_this_controller_kebab_name = $this->serviceManager->baseKebabName();
        return redirect()->route($str_this_controller_kebab_name);
    }
}
