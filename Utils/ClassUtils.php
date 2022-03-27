<?php


class ClassUtils
{

    static public function GetClassByName(string $classname, array $properties = []): ?object
    {

        $object = null;
        try {

            $object = new $classname(...$properties);
        } catch (Error $e) {
            Logger::Warning($e->getMessage());
        }

        return $object;
    }
}
