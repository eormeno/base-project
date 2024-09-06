<?php

namespace App\Utils;

use Throwable;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Database\Eloquent\Model;

class ReflectionUtils
{
    public static function getKebabClassName($gen_instance, string $suffix_to_remove = ""): string
    {
        if (!$gen_instance instanceof ReflectionClass) {
            $gen_instance = new ReflectionClass($gen_instance);
        }
        $short_class_name = $gen_instance->getShortName();
        if ($suffix_to_remove) {
            $short_class_name = substr($short_class_name, 0, -strlen($suffix_to_remove));
        }
        return CaseConverters::pascalToKebab($short_class_name);
    }

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

    public static function invokeMethod($state_instance, $method, $data)
    {
        $reflection = new ReflectionClass($state_instance);
        $parametersValues = self::getMethodParametersValues($state_instance, $method, $data);
        return $reflection->getMethod($method)->invokeArgs($state_instance, $parametersValues);
    }

    public static function getModelAttributeNames(Model $object)
    {
        $attributeNames = [];
        // add the accessor attributes
        $accessors = $object->getMutatedAttributes();
        foreach ($accessors as $accessor) {
            $attributeNames[] = $accessor;
        }
        // add the appended attributes
        $appends = $object->getAppends();
        foreach ($appends as $append) {
            $attributeNames[] = $append;
        }
        // add the fillable attributes
        $fillable = $object->getFillable();
        foreach ($fillable as $fill) {
            $attributeNames[] = $fill;
        }
        // add the relations
        $relations = self::getModelRelations($object);
        foreach ($relations as $relation) {
            $attributeNames[] = $relation;
        }
        return $attributeNames;
    }

    public static function getModelRelations(Model $model)
    {
        $relations = [];
        $reflection = new ReflectionClass($model);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            if ($method->getNumberOfParameters() > 0) {
                continue; // No queremos métodos con parámetros.
            }
            $returnType = $method->getReturnType();
            if ($returnType === null) {
                continue; // No queremos métodos sin tipo de retorno.
            }
            // Si el tipo de retorno es una subclase de Illuminate\Database\Eloquent\Relations\Relation
            if (is_subclass_of($returnType->getName(), 'Illuminate\Database\Eloquent\Relations\Relation')) {
                $relations[] = $method->name;
            }
        }
        return $relations;
    }

}
