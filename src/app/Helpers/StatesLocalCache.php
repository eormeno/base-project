<?php

namespace App\Helpers;

use ReflectionClass;
use App\FSM\StateInterface;
use App\Utils\CaseConverters;

class StatesLocalCache
{
    private const INSTANCED_STATES_KEY = 'instanced_states';

    public static function getStateInstance(ReflectionClass $reflection_state_class)
    {
        self::findRegisteredStateInstance($reflection_state_class);
        $short_class_name = $reflection_state_class->getShortName();
        $state_kebab_name = CaseConverters::pascalToKebab($short_class_name);
        return session(self::INSTANCED_STATES_KEY)[$state_kebab_name];
    }

    public static function findRegisteredStateInstance(ReflectionClass $reflection_state_class): StateInterface
    {
        if (!in_array(StateInterface::class, $reflection_state_class->getInterfaceNames())) {
            throw new \Exception("The state class must implement the StateInterface.");
        }
        $is_need_restoring = false;
        if (!session()->has(self::INSTANCED_STATES_KEY)) {
            session()->put(self::INSTANCED_STATES_KEY, []);
            $is_need_restoring = true;
        }
        $str_short_name = $reflection_state_class->getShortName();
        $state_kebab_name = CaseConverters::pascalToKebab($str_short_name);
        $arr_instanced_states = session(self::INSTANCED_STATES_KEY);
        if (!array_key_exists($state_kebab_name, $arr_instanced_states)) {
            $sta_new_instance = $reflection_state_class->newInstance();
            $sta_new_instance->setNeedRestoring($is_need_restoring);
            $arr_instanced_states[$state_kebab_name] = $sta_new_instance;
            session()->put(self::INSTANCED_STATES_KEY, $arr_instanced_states);
        }
        return $arr_instanced_states[$state_kebab_name];
    }

    public static function getStateInstanceFromKey(string $state_dashed_name) : ReflectionClass
    {
        $instanced_states = session(self::INSTANCED_STATES_KEY);
        return $instanced_states[$state_dashed_name]::StateClass();
    }

    public static function reset(): void
    {
        session()->forget(self::INSTANCED_STATES_KEY);
    }
}
