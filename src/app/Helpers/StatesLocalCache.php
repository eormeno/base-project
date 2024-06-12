<?php

namespace App\Helpers;

use ReflectionClass;
use App\FSM\StateInterface;

class StatesLocalCache
{
    private const INSTANCED_STATES_KEY = 'instanced_states';

    public static function getStateInstance(ReflectionClass $rflStateClass)
    {
        self::findRegisteredStateInstance($rflStateClass);
        $strClassName = $rflStateClass->getName();
        return session(self::INSTANCED_STATES_KEY)[$strClassName];
    }

    public static function findRegisteredStateInstance(ReflectionClass $rflStateClass): StateInterface
    {
        if (!in_array(StateInterface::class, $rflStateClass->getInterfaceNames())) {
            throw new \Exception("The state class must implement the StateInterface.");
        }
        $isNeedRestoring = false;
        if (!session()->has(self::INSTANCED_STATES_KEY)) {
            session()->put(self::INSTANCED_STATES_KEY, []);
            $isNeedRestoring = true;
        }
        $strClassName = $rflStateClass->getName();
        $arrInstancedStates = session(self::INSTANCED_STATES_KEY);
        if (!array_key_exists($strClassName, $arrInstancedStates)) {
            $staNewInstance = $rflStateClass->newInstance();
            $staNewInstance->setNeedRestoring($isNeedRestoring);
            $arrInstancedStates[$strClassName] = $staNewInstance;
            session()->put(self::INSTANCED_STATES_KEY, $arrInstancedStates);
        }
        return $arrInstancedStates[$strClassName];
    }

    public static function reset(): void
    {
        session()->forget(self::INSTANCED_STATES_KEY);
    }
}
