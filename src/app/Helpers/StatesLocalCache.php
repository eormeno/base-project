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
        self::registerStateInstance($reflection_state_class);
        $short_class_name = $reflection_state_class->getShortName();
        $state_kebab_name = CaseConverters::pascalToKebab($short_class_name);
        return session(self::INSTANCED_STATES_KEY)[$state_kebab_name];
    }

    public static function registerStateInstance(ReflectionClass $reflection_state_class): void
    {
        if (!in_array(StateInterface::class, $reflection_state_class->getInterfaceNames())) {
            throw new \Exception("The state class must implement the StateInterface.");
        }
        $need_restoring = false;
        if (!session()->has(self::INSTANCED_STATES_KEY)) {
            session()->put(self::INSTANCED_STATES_KEY, []);
            $need_restoring = true;
        }
        $str_short_name = $reflection_state_class->getShortName();
        $state_kebab_name = CaseConverters::pascalToKebab($str_short_name);
        $instanced_states = session(self::INSTANCED_STATES_KEY);
        if (!array_key_exists($state_kebab_name, $instanced_states)) {
            $new_instance = $reflection_state_class->newInstance();
            $new_instance->setNeedRestoring($need_restoring);
            $instanced_states[$state_kebab_name] = $new_instance;
            session()->put(self::INSTANCED_STATES_KEY, $instanced_states);
        }
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
