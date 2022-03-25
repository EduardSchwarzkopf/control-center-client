<?php


class ClassUtils
{

    static public function GetClassByName(string $classname): ?object
    {

        $object = null;
        try {
            $object = new $classname;
        } catch (Exception $e) {
            Logger::Error($e);
        }

        return $object;
    }
}
