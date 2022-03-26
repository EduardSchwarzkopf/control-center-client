<?php

abstract class Controller
{
    abstract public function Get(): array;
    abstract public function Post(Request $request): array;
    abstract public function Put(Request $request): array;
    abstract public function Delete(Request $request): array;

    static public function GetControllerByName(string $name)
    {
        $classname = $name . 'Controller';
        return ClassUtils::GetClassByName($classname);
    }
}
