<?php

namespace App\Helpers;

use ReflectionClass;
use App\FSM\StateInterface;

class StatesLocalCache
{
    private const INSTANCED_STATES_KEY = 'instanced_states';

    public static function getStateInstance(ReflectionClass $rflStateClass, int $id)
    {
        self::findRegisteredStateInstance($rflStateClass, $id);
        $strStateInstanceForObject = $rflStateClass->getName() . $id;
        return session(self::INSTANCED_STATES_KEY)[$strStateInstanceForObject];
    }

    public static function findRegisteredStateInstance(ReflectionClass $rflStateClass, int $id): StateInterface
    {
        if (!in_array(StateInterface::class, $rflStateClass->getInterfaceNames())) {
            throw new \Exception("The state class must implement the StateInterface.");
        }
        $isNeedRestoring = false;
        if (!session()->has(self::INSTANCED_STATES_KEY)) {
            session()->put(self::INSTANCED_STATES_KEY, []);
            $isNeedRestoring = true;
        }
        $strStateInstanceForObject = $rflStateClass->getName() . $id;
        $arrInstancedStates = session(self::INSTANCED_STATES_KEY);
        if (!array_key_exists($strStateInstanceForObject, $arrInstancedStates)) {
            $staNewInstance = $rflStateClass->newInstance();
            $staNewInstance->setNeedRestoring($isNeedRestoring);
            $arrInstancedStates[$strStateInstanceForObject] = $staNewInstance;
            session()->put(self::INSTANCED_STATES_KEY, $arrInstancedStates);
        }
        return $arrInstancedStates[$strStateInstanceForObject];
    }

    public static function reset(): void
    {
        session()->forget(self::INSTANCED_STATES_KEY);
    }
}
