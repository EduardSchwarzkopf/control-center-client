<?php


class ClassUtils
{

    static public function GetClassByName(string $classname, array $properties = []): ?object
    {

        $object = null;
        try {

            $object = new $classname(...$properties);
        } catch (Exception $e) {
            Logger::Error($e);
        }

        return $object;
    }
}
