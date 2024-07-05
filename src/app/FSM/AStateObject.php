<?php

namespace App\FSM;

class AStateObject
{
    const KEY_INSTANCE = 'AStateoOjectInstance';
    const KEY_ALIAS = 'alias';

    public static function SessionInstance(): AStateObject
    {
        if (session()->has(self::KEY_INSTANCE)) {
            $instance = session(self::KEY_INSTANCE);
            return $instance;
        }
        $instance = new AStateObject();
        session()->put(self::KEY_INSTANCE, $instance);
        return $instance;
    }

    public final function register(IStateModel $model)
    {
        if (!session()->has(self::KEY_ALIAS)) {
            session()->put(self::KEY_ALIAS, []);
        }
        $aliases = session()->get(self::KEY_ALIAS);
        $alias = $model->getAlias();
        $aliases[$alias] = $model;
        session()->put(self::KEY_ALIAS, $aliases);
    }

    public final function get(string $alias): IStateModel|null
    {
        if (!session()->has(self::KEY_ALIAS)) {
            return null;
        }
        $aliases = session(self::KEY_ALIAS);
        if (!array_key_exists($alias, $aliases)) {
            return null;
        }
        return $aliases[$alias];
    }

    public final function getModels(array $aliases): array
    {
        $models = [];
        foreach ($aliases as $alias) {
            $model = $this->get($alias);
            if ($model === null) {
                continue;
            }
            $models[] = $model;
        }
        return $models;
    }

    public final function clear()
    {
        session()->forget(self::KEY_ALIAS);
    }
}
