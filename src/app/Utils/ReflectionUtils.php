<?php

namespace App\Utils;

use ReflectionClass;
use ReflectionMethod;

class ReflectionUtils
{
    public static function getMethods($class)
    {
        $reflection = new ReflectionClass($class);
        return $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    }

    public static function getMethodNames($class)
    {
        $methods = self::getMethods($class);
        $methodNames = [];
        foreach ($methods as $method) {
            $methodNames[] = $method->name;
        }
        return $methodNames;
    }

    public static function getMethodNamesStartingWith($class, $prefix)
    {
        $methods = self::getMethods($class);
        $methodNames = [];
        foreach ($methods as $method) {
            if (strpos($method->name, $prefix) === 0) {
                $methodNames[] = $method->name;
            }
        }
        return $methodNames;
    }

    public static function getMethodParameters($class, $method)
    {
        $reflection = new ReflectionClass($class);
        $method = $reflection->getMethod($method);
        $parameters = $method->getParameters();
        $parameterNames = [];
        foreach ($parameters as $parameter) {
            $parameterNames[] = $parameter->name;
        }
        return $parameterNames;
    }

    public static function getMethodParametersValues($class, $method, $data)
    {
        $parameters = self::getMethodParameters($class, $method);
        $parametersValues = [];
        foreach ($parameters as $parameter) {
            $parametersValues[$parameter] = $data[$parameter] ?? null;
        }
        return $parametersValues;
    }

    public static function invokeMethod($class, $method, $data)
    {
        $reflection = new ReflectionClass($class);
        $parametersValues = self::getMethodParametersValues($class, $method, $data);
        $reflection->getMethod($method)->invokeArgs($class, $parametersValues);
    }
}
